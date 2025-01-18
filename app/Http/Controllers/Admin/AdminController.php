<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\AdminAccountInfoNotification;

class AdminController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index() {
    //
    $all_user = Admin::latest()
                     ->whereNull('deleted_at')
                     ->get();

    $roles = Role::latest() -> get();

    return view('admin.pages.user.index', [
      'all_user'      => $all_user,
      'form_type'     => 'create',
      'roles'         => $roles
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $roles = Role::where('slug', '!=', 'student')
                 ->where('slug', '!=', 'teacher')
                 ->where('slug', '!=', 'staff')
                 ->where('slug', '!=', 'sadmin')
                 ->latest()
                 ->get();

    return view('admin.pages.user.create', [
      'roles' => $roles
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request) {
    // Data Validation
    $request->validate([
      'role'        => ['required'], // Ensure the role field is selected
      'name'        => ['required'],
      'email'       => 'required|email|unique:admins',
      'cell'        => ['required', 'starts_with:01,8801,+8801', 'regex:/^\+?[0-9]{11,15}$/', 'unique:admins'], // Regex for phone number validation
      'username'    => ['required', 'unique:admins', 'min:4', 'max:10'],
      'user_id'     => 'required|string|unique:admins', // Ensure user_id is required
    ], [
      'role.required' => 'Please select a role.', // Custom error message
    ]);

    // Generate random password
    $pass_string = str_shuffle('qwertyuiopasdfghjklzxcvbnm0123456789ABCDEFGHIJKLMNOPQRSTWXYZ');
    $pass = substr($pass_string, 3, 6); // Now take string from position: 3 to 9, 6 digit

    // Store data
    $user = Admin::create([
      'user_id'   => $request->user_id,
      'role_id'   => $request->role,
      'name'      => $request->name,
      'email'     => $request->email,
      'cell'      => $request->cell,
      'username'  => $request->username,
      'password'  => Hash::make($pass),
      'status' => '1'
    ]);

    // Send notification to user email
    $user->notify(new AdminAccountInfoNotification([$user['name'], $pass]));

    return back()->with('success', 'Admin user created!');
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    try {
      $user = Admin::with('role')->findOrFail($id);

      return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'user_id' => $user->user_id,
        'email' => $user->email,
        'cell' => $user->cell,
        'gender' => $user->gender,
        'dept' => $user->dept,
        'semester' => $user->semester,
        'semester_year' => $user->semester_year,
        'hall' => $user->hall,
        'room' => $user->room,
        'dob' => $user->dob,
        'address' => $user->address,
        'bio' => $user->bio,
        'status' => $user->status,
        'role' => $user->role,
        'photo_url' => $user->photo ? asset('storage/image/profile/' . $user->photo) : asset('storage/image/profile/avatar.png')
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Error fetching user data',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $user = Admin::findOrFail($id);

    $roles = Role::latest() -> get(); // get roles

    // return the new dedicated edit view
    return view('admin.pages.user.edit', [
      'user' => $user,
      'roles' => $roles
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    // Find the user
    $user = Admin::findOrFail($id);

    // Data Validation
    $request->validate([
      'role_id'     => ['required', 'exists:roles,id'], // Ensure the role exists in the roles table
      'name'        => ['required'],
      'email'       => 'required|email|unique:admins,email,' . $id,
      'cell'        => ['required', 'starts_with:01,8801,+8801', 'regex:/^\+?[0-9]{11,15}$/', 'unique:admins,cell,' . $id],
      'username'    => ['required', 'unique:admins,username,' . $id, 'min:4', 'max:10'],
      'user_id'     => 'required|string|unique:admins,user_id,' . $id,
      'photo'       => 'nullable|image|max:2048',
    ], [
      'role_id.required' => 'Please select a role.',
    ]);

    // Fetch the selected role
    $role = Role::findOrFail($request->role_id);

    // Update user data
    $user->update([
      'user_id'   => $request->user_id,
      'role_id'   => $role->id,
      'name'      => $request->name,
      'email'     => $request->email,
      'cell'      => $request->cell,
      'username'  => $request->username,
      'user_type' => $role->slug, // Set user_type dynamically
      'gender'    => $request->gender,
    ]);

    // Handle photo upload if provided
    if ($request->hasFile('photo')) {
      // Delete the old photo if it exists
      if ($user->photo && file_exists(public_path('storage/image/profile/' . $user->photo))) {
        unlink(public_path('storage/image/profile/' . $user->photo));
      }

      // Store new photo
      $path = $request->file('photo')->store('image/profile', 'public');
      $user->update(['photo' => basename($path)]);
    }

    return redirect()->route('admin-user.index')->with('success', 'Admin user updated successfully!');
  }

  /**
   * Display a listing of the trashed Users.
   */
  public function trash()
  {
    $users = Admin::onlyTrashed()->latest()->get();

    return view('admin.pages.user.trash', [
      'users' => $users,
    ]);
  }

  /**
   * Soft delete the specified User.
   */
  public function destroy(string $id) {
    // search id to trash
    $user = Admin::findOrFail($id);
    $user -> delete(); // This will soft delete

    // return with a success message
    return back() -> with('success-main', $user -> name . ', moved to trash successfully');
  }

  /**
   * Restore the specified user from trash.
   */
  public function restore($id)
  {
    $user = Admin::onlyTrashed()->findOrFail($id);
    $user->restore();

    return back()->with('success-main', 'User restored successfully');
  }

  /**
   * Permanently delete the specified user.
   */
  public function forceDelete($id)
  {
    $user = Admin::onlyTrashed()->findOrFail($id);
    $user->forceDelete();

    return back()->with('success-main', 'User permanently deleted');
  }

  /*****************************************************************
   * Custom Methods Section
   *****************************************************************/
  /**
   * Status update
   */
  public function updateStatus($id) {
    // catch data
    $data = Admin::findOrFail($id);

    // change status
    if ($data -> status) {
      $data -> update([
        'status' => false
      ]);
    } else {
      $data -> update([
        'status' => true
      ]);
    }

    // return with a success message
    return back() -> with('success-main', $data -> name . ' status update successful');
  }

  /**
   * Trash update
   */
  public function updateTrash($id) {
    // catch data
    $data = Admin::findOrFail($id);

    // change status
    if ($data -> trash) {

      $data -> update([
        'trash' => false
      ]);
      // return with a success message
      return back() -> with('success-main', $data -> name . ' data moved to Admin user');

    } else {
      $data -> update([
        'trash' => true
      ]);
      // return with a success message
      return back() -> with('success-main', $data -> name . ' data moved to Trash');
    }

  }

  /**
   * Display Trash Users
   */
  public function trashUsers() {
    //
    $all_admin = Admin::latest() -> where('trash', true) -> get();

    return view('admin.pages.user.trash', [
      'all_admin'      => $all_admin,
      'form_type'     => 'trash',
    ]);
  }
}
