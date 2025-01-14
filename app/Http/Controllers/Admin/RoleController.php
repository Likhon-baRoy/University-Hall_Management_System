<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index() {
    // get latest roles with admins
    $roles = Role::with('admins')->latest()->get();
    // get latest permissions from Permission table
    $permissions = Permission::latest()->get();

    return view('admin.pages.user.role.index', [
      'roles'        => $roles,
      'form_type'    => 'create',
      'permissions'  => $permissions
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
      'name'    => ['required']
    ]);
    // store Role
    Role::create([
      'name'    => $request -> name,
      'slug'    => Str::slug($request -> name),
      'permissions' => json_encode($request -> permission ?? [])
    ]);

    return back() -> with('success', 'Role added successfully');
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
  public function edit(string $id) {
    // get latest role
    $roles = Role::latest() -> get();

    // get latest permission from Permission table
    $permissions = Permission::latest() -> get();

    // find role data by id
    $edit = Role::findOrFail($id);

    // return the get data
    return view('admin.pages.user.role.index', [
      'roles'        => $roles,
      'form_type'    => 'edit',
      'permissions'  => $permissions,
      'edit'         => $edit
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id) {
    // get the data to update
    $update_data = Role::findOrFail($id);

    // update data
    $update_data -> update([
      'name'    => $request -> name,
      'slug'    => Str::slug($request -> name),
      'permissions' => json_encode($request -> permission ?? [])
    ]);

    // return with a success message and redirect to the role index page
    return redirect()->route('role.index')->with('success-main', $request->name . ' role updated successfully');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id) {
    // search id to delete
    $data = Role::findOrFail($id);

    // delete if found
    $data -> delete();

    // return with a success message
    return back() -> with('success-main', 'Role deleted successfully');
  }
}
