<?php

namespace App\Http\Controllers\Admin;

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
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request) {
    // Data Validation
    $request -> validate([
      'role'        => ['required'], // Ensure the role field is selected
      'name'        => ['required'],
      'email'       => 'required|email|unique:admins',
      'cell'        => ['required', 'starts_with:01,8801,+8801', 'regex:/^\+?[0-9]{11,15}$/', 'unique:admins'], // Regex for phone number validation
      'username'    => ['required', 'unique:admins', 'min:4', 'max:10'],
    ], [
      'role.required' => 'Please select a role.', // Custom error message
    ]);

    // genarete random password
    $pass_string = str_shuffle('qwertyuiopasdfghjklzxcvbnm0123456789ABCDEFGHIJKLMNOPQRSTWXYZ');
    $pass = substr($pass_string, 3, 6); // now take string from position: 3 to 9, 6 digit

    // store data
    $user = Admin::create([
      'role_id'   => $request -> role,
      'name'      => $request -> name,
      'email'     => $request -> email,
      'cell'      => $request -> cell,
      'username'  => $request -> username,
      'password'  => Hash::make($pass),
    ]);

    // send notification to user email
    $user -> notify( new AdminAccountInfoNotification( [$user['name'], $pass] ));

    return back() -> with('success','Admin user created!');
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
        'photo_url' => $user->photo ? asset('storage/img/' . $user->photo) : asset('storage/img/avatar.png')
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
    //
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
