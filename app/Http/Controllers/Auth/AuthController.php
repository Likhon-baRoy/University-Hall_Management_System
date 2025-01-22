<?php
// app/Http/Controllers/Auth/AuthController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Hall;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Role;
use App\Models\StudentVerification;
use App\Models\TeacherVerification;
use App\Models\StaffVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\VerifiesUniversityMembers;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
  use VerifiesUniversityMembers;

  // Show Login page
  public function showLoginPage()
  {
    return view('admin.pages.login');
  }

  // Show Registration page
  public function showRegisterPage(Request $request)
  {
    // Get booking data from room parameter
    $bookingData = $this->getBookingData($request);
    $allowedUserTypes = ['student', 'teacher', 'staff'];

    // Debug logging
    \Log::info('Room Parameter:', ['room' => $request->room]);
    \Log::info('Booking Data:', $bookingData);

    return view('admin.pages.register', compact('bookingData', 'allowedUserTypes'));
  }

  // Handle Admin Login
  public function login(Request $request)
  {
    // Validate request
    $request->validate([
      'auth' => 'required',
      'password' => 'required'
    ]);

    // Get admin by username, email, or cell
    $admin = Admin::where('username', $request->auth)
                  ->orWhere('email', $request->auth)
                  ->orWhere('cell', $request->auth)
                  ->first();

    if ($admin && Hash::check($request->password, $admin->password)) {
      if ($admin->status && !$admin->trash) {
        Auth::guard('admin')->login($admin);

        // If admin came from booking page, redirect there
        if ($request->session()->has('intended_room_booking')) {
          $roomId = $request->session()->pull('intended_room_booking');
          return redirect()->route('hall-booking.book', ['room' => $roomId]);
        }
        return redirect()->route('admin.dashboard');
      } else {
        return redirect()->route('login')->with('danger', 'This account is restricted');
      }
    }

    return redirect()->route('login')->with('warning', 'Email/Password not matched');
  }

  // Handle Admin Registration with role validation
  public function register(Request $request)
  {
    Log::info('Registration attempt:', [
      'request_data' => $request->except('password')
    ]);

    try {
      // First validate the basic form data
      $validated = $request->validate([
        'name' => [
          'required',
          'string',
          'max:255',
          'regex:/^[A-Z][a-zA-Z]*(?:\s[A-Z][a-zA-Z]*)*$/u',
        ],
        'user_id' => 'required|string|unique:admins',
        'username' => 'required|string|unique:admins',
        'email' => 'required|string|email|unique:admins',
        'cell' => 'required|string|unique:admins',
        'password' => 'required|string|min:8|confirmed',
        'user_type' => 'required|in:student,teacher,staff',
        'gender' => 'required|in:male,female,other',
        'dept' => 'required|string',
        'semester_type' => 'required|in:trimester,bi-semester',
        'semester' => 'required|in:summer,fall,winter',
        'semester_year' => 'required|integer|digits:4',
        'hall' => 'required|string',
        'room' => 'required|string',
        'seat' => $request->has('room_id') ? 'required|string' : 'nullable|string',
      ], [
        'name.regex' => 'Each word in the name must start with a capital letter and contain only letters.',
      ]);

      // Get hall ID for verification
      $hall = Hall::where('name', $request->hall)
                  ->firstOrFail();

      // Now verify university credentials including gender match
      if (!$this->verifyUniversityMember(
        $request->user_id,
        $request->email,
        $request->user_type,
        $hall->id
      )) {
        throw ValidationException::withMessages([
          'credentials' => ['Verification failed. Please check your credentials.']
        ]);
      }

      \DB::beginTransaction();

      try {
        // Find the appropriate role
        $role = Role::where('slug', $request->user_type)->firstOrFail();

        // Create admin user
        $adminData = array_merge($validated, [
          'password' => Hash::make($validated['password']),
          'status' => false,
          'role_id' => $role->id
        ]);

        $admin = Admin::create($adminData);

        // Handle seat booking if applicable
        if ($request->filled('room_id') && $request->filled('seat')) {
          $this->handleSeatBooking($request, $admin);
        }

        \DB::commit();

        return redirect() ->route('login')
                          ->with('success', 'Registration successful! Your account will not activate until you pay.');

      } catch (\Exception $e) {
        \DB::rollBack();
        throw $e;
      }

    } catch (ValidationException $e) {
      Log::warning('Validation failed:', [
        'errors' => $e->errors(),
        'user_type' => $request->user_type ?? 'not provided',
        'user_id' => $request->user_id ?? 'not provided'
      ]);

      return back() ->withErrors($e->errors())
                    ->withInput();

    } catch (\Exception $e) {
      Log::error('Registration failed:', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      return back() ->withErrors(['error' => 'Registration failed. Please try again.'])
                    ->withInput();
    }
  }

  // Add this new method to verify credentials
  private function verifyUserCredentials($userType, $userId, $email, $department)
  {
    $verificationModel = match($userType) {
      'student' => StudentVerification::class,
      'teacher' => TeacherVerification::class,
      'staff' => StaffVerification::class,
      default => null
    };

    if (!$verificationModel) {
      return false;
    }

    $verification = $verificationModel::where([
      'user_id' => $userId,
      'email' => $email,
      'department' => $department,
      'is_registered' => false
    ])->first();

    if ($verification) {
      $verification->update(['is_registered' => true]);
      return true;
    }

    return false;
  }

  // Helper method for handling seat booking
  private function handleSeatBooking(Request $request, Admin $admin)
  {
    \Log::info('Handling seat booking:', [
      'room_id' => $request->room_id,
      'seat_name' => $request->seat,
      'admin_id' => $admin->id
    ]);

    $seat = Seat::where([
      'room_id' => $request->room_id,
      'name' => $request->seat,  // Changed from 'number' to 'name'
      'status' => true
    ])->first();

    if ($seat) {
      \Log::info('Found seat to book:', ['seat_id' => $seat->id]);

      $seat->update([
        'status' => false,
        'admin_id' => $admin->id
      ]);

      \Log::info('Successfully booked seat');
    } else {
      \Log::warning('Seat not found or already booked', [
        'room_id' => $request->room_id,
        'seat_name' => $request->seat
      ]);
    }
  }

  // Helper method for getting booking data
  private function getBookingData(Request $request)
  {
    $defaultData = [
      'hall' => '',
      'room_id' => '',
      'room' => '',
      'available_seats' => []
    ];

    // Get room ID from route parameter
    $roomId = $request->route('room');
    \Log::info('Attempting to get room data', ['room_id' => $roomId]);

    if (!$roomId) {
      \Log::info('No room ID provided');
      return $defaultData;
    }

    try {
      // Find the room with its relationships
      $room = Room::with([
        'hall',
        'seats' => function($query) {
          $query->where('status', true);
        }
      ])->find($roomId);

      // Debug the room query
      \Log::info('Room query result:', [
        'room_found' => $room ? 'yes' : 'no',
        'room_data' => $room,
        'hall_data' => $room?->hall,
        'seats_count' => $room?->seats?->count()
      ]);

      if (!$room) {
        \Log::error('Room not found', ['room_id' => $roomId]);
        return $defaultData;
      }

      if (!$room->hall) {
        \Log::error('Hall not found for room', ['room_id' => $roomId]);
        return $defaultData;
      }

      $data = [
        'hall' => $room->hall->name,
        'room_id' => $room->id,
        'room' => $room->name,
        'available_seats' => $room->seats->pluck('name')->toArray()
      ];

      \Log::info('Generated booking data:', $data);

      return $data;
    } catch (\Exception $e) {
      \Log::error('Error getting booking data', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);
      return $defaultData;
    }
  }

  // Handle Admin Logout
  public function logout()
  {
    Auth::guard('admin')->logout();
    return redirect()->route('login');
  }

  public function showForgotPasswordForm()
  {
    return view('admin.pages.auth.forgot-password');
  }

  public function sendResetLink(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:admins'
    ]);

    // Specify the admin broker here
    $status = Password::broker('admins')->sendResetLink(
      $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
    ? back()->with('success', 'Password reset link has been sent to your email')
         : back()->withErrors(['email' => __($status)]);
  }

  public function showResetForm($token)
  {
    return view('admin.pages.auth.reset-password', ['token' => $token]);
  }

  public function resetPassword(Request $request)
  {
    $request->validate([
      'token' => 'required',
      'email' => 'required|email',
      'password' => 'required|confirmed|min:8',
    ]);

    // Use the admin broker here as well
    $status = Password::broker('admins')->reset(
      $request->only('email', 'password', 'password_confirmation', 'token'),
      function ($user, $password) {
        $user->forceFill([
          'password' => Hash::make($password)
        ])->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));
      }
    );

    return $status === Password::PASSWORD_RESET
    ? redirect()->route('login')->with('success', 'Your password has been reset!')
         : back()->withErrors(['email' => [__($status)]]);
  }
}
