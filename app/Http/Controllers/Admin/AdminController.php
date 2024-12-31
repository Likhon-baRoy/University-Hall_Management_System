<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index() {
    //
    $all_admin = Admin::latest() -> get();

    $roles = Role::latest() -> get();

    return view('admin.pages.user.index', [
      'all_admin'      => $all_admin,
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
      'name'        => ['required'],
      'email'       => 'required|email|unique:students',
      'cell'        => ['required', 'starts_with:01,8801,+8801', 'regex:/^\+?[0-9]{11,15}$/', 'unique:students'], // Regex for phone number validation
      'username'    => ['required', 'unique:admins', 'min:4', 'max:10'],
    ]);

    // genarete random password
    $pass_string = str_shuffle('qwertyuiopasdfghjklzxcvbnm0123456789ABCDEFGHIJKLMNOPQRSTWXYZ');
    $pass = substr($pass_string, 3, 6); // now take string from position: 3 to 9, 6 digit

    // store data
    Admin::create([
      'name'      => $request -> name,
      'email'     => $request -> email,
      'cell'      => $request -> cell,
      'username'  => $request -> username,
      'password'  => Hash::make($pass),
    ]);
    return back() -> with('success','Admin user created!');
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
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
    return back() -> with('success-main','Admin user created!');
  }
}
