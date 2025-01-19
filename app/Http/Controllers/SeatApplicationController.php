<?php

namespace App\Http\Controllers;

use App\Models\SeatApplication;
use App\Models\Seat;
use App\Models\Admin;
use App\Notifications\ApplicationProcessedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeatApplicationController extends Controller
{
  public function adminIndex()
  {
    $user = Auth::guard('admin')->user();
    $permissions = json_decode($user->role->permissions ?? '[]');
    $applications = SeatApplication::latest()->get();

    return view('admin.pages.application.admin-index', compact('applications', 'permissions'));
  }

  public function userIndex()
  {
    $user = Auth::guard('admin')->user();
    $permissions = json_decode($user->role->permissions ?? '[]');
    $applications = SeatApplication::where('user_id', $user->id)
                                   ->latest()
                                   ->paginate(10);

    return view('admin.pages.application.user-index', compact('applications', 'permissions'));
  }

  public function store(Request $request)
  {
    // Validate the request
    $validated = $request->validate([
      'application_type' => 'required|in:change,cancel',
      'reason' => 'required|string|min:50',
      'requested_seat_id' => 'required_if:application_type,change|nullable|exists:seats,id',
    ]);

    $user = Auth::guard('admin')->user();

    // Check if user has a current seat
    if ($request->application_type === 'cancel' && !$user->seat) {
      return back()->with('error', 'You do not have a seat to cancel.');
    }

    // Check for pending applications
    $pendingApplication = SeatApplication::where('user_id', $user->id)
                                         ->where('status', 'pending')
                                         ->first();

    if ($pendingApplication) {
      return back()->with('error', 'You already have a pending application.');
    }

    try {
      DB::beginTransaction();

      // Create the application
      $application = SeatApplication::create([
        'user_id' => $user->id,
        'current_seat_id' => $user->seat,
        'requested_seat_id' => $request->application_type === 'change' ? $request->requested_seat_id : null,
        'application_type' => $request->application_type,
        'reason' => $request->reason,
        'status' => 'pending'
      ]);

      // If it's a change request, check if the requested seat is still available
      if ($request->application_type === 'change' && $request->requested_seat_id) {
        $requestedSeat = Seat::find($request->requested_seat_id);
        if (!$requestedSeat || !$requestedSeat->status) {
          DB::rollback();
          return back()->with('error', 'The requested seat is no longer available.');
        }
      }

      DB::commit();
      return redirect()->route('applications.user.index')
                       ->with('success', 'Application submitted successfully.');

    } catch (\Exception $e) {
      DB::rollback();
      Log::error('Seat application error: ' . $e->getMessage());
      return back()->with('error', 'Error submitting application. Please try again.');
    }
  }

  public function create()
  {
    $user = Auth::guard('admin')->user();
    $permissions = json_decode($user->role->permissions ?? '[]');

    // Use the relationship instead of direct ID lookup
    $currentSeat = $user->assignedSeat;

    // If there's a seat ID but no seat found, log it as an error
    if ($user->seat && !$currentSeat) {
      \Log::warning('Invalid seat assignment found', [
        'user_id' => $user->id,
        'invalid_seat_id' => $user->seat
      ]);

      // Optionally, clean up the invalid reference
      // $user->update(['seat' => null]);
    }

    $availableSeats = Seat::whereHas('room.hall', function ($query) use ($user) {
      $query->where('gender', $user->gender);
    })
                          ->where('status', true)
                          ->when($currentSeat, function($query) use ($currentSeat) {
                            return $query->where('id', '!=', $currentSeat->id);
                          })
                          ->get();

    return view('admin.pages.application.create', compact('currentSeat', 'availableSeats', 'permissions'));
  }

  public function show(SeatApplication $application)
  {
    return view('admin.pages.application.show', compact('application'));
  }

  public function process(Request $request, SeatApplication $application)
  {
    $request->validate([
      'status' => 'required|in:approved,rejected',
      'admin_note' => 'required|string|min:10',
    ]);

    try {
      DB::beginTransaction();

      if ($application->status !== 'pending') {
        throw new \Exception('This application has already been processed.');
      }

      // Process the application
      $application->update([
        'status' => $request->status,
        'admin_note' => $request->admin_note,
        'processed_at' => now(),
        'processed_by' => auth()->id(),
      ]);

      if ($request->status === 'approved') {
        if ($application->application_type === 'change') {
          // For seat change requests, we only need to verify the requested seat
          if (!$application->requestedSeat) {
            throw new \Exception('Requested seat not found.');
          }

          // Make sure requested seat is still available
          if (!$application->requestedSeat->status) {
            throw new \Exception('Requested seat is no longer available.');
          }

          // If user has a current seat, make it available
          if ($application->currentSeat) {
            $application->currentSeat->update(['status' => true]);
          }

          // Mark requested seat as occupied
          $application->requestedSeat->update(['status' => false]);

          // Update user's seat assignment
          $application->user->update(['seat' => $application->requested_seat_id]);
        }
      }

      // Send notification to user
      $application->user->notify(new ApplicationProcessedNotification($application));

      DB::commit();

      return redirect()->route('applications.index')
                       ->with('success', "Application {$request->status} successfully.");

    } catch (\Exception $e) {
      DB::rollback();
      Log::error('Error processing application: ' . $e->getMessage());
      return back()->with('error', 'Error processing application: ' . $e->getMessage());
    }
  }

  // For handling seat cancellation
  public function destroy(SeatApplication $application)
  {
    try {
      DB::beginTransaction();

      $user = $application->user;

      // Update the application status
      $application->update([
        'status' => 'approved',
        'admin_note' => request('admin_note'),
        'processed_at' => now(),
        'processed_by' => auth()->id(),
      ]);

      // Clear user's seat
      if ($user->seat) {
        $currentSeat = $user->assignedSeat;
        if ($currentSeat) {
          $currentSeat->update(['status' => true]);
        }
        $user->update(['seat' => null]);
      }

      // Send notification
      $user->notify(new ApplicationProcessedNotification($application));

      DB::commit();
      return redirect()->route('applications.index')
                       ->with('success', 'Seat cancellation approved successfully.');

    } catch (\Exception $e) {
      DB::rollback();
      Log::error('Error cancelling seat: ' . $e->getMessage());
      return back()->with('error', 'Error cancelling seat: ' . $e->getMessage());
    }
  }

  // In SeatApplicationController
  public function archive()
  {
    $archivedApplications = SeatApplication::onlyTrashed()
                                           ->with(['user', 'currentSeat', 'requestedSeat', 'processor'])
                                           ->latest('deleted_at')
                                           ->paginate(15);

    return view('admin.pages.application.archive', compact('archivedApplications'));
  }

  public function forceDelete(SeatApplication $application)
  {
    try {
      $application->forceDelete();
      return redirect()->route('applications.archive')
                       ->with('success', 'Application permanently deleted.');
    } catch (\Exception $e) {
      Log::error('Error permanently deleting application: ' . $e->getMessage());
      return back()->with('error', 'Error deleting application.');
    }
  }
}
