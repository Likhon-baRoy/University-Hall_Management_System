<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index() {
    // sort the data in latest order
    $permissions = Permission::latest() -> get();

    // return the get data
    return view('admin.pages.user.permission.index', [
      'all_permission'    => $permissions,
      'form_type'         => 'create'
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
      'name'    => 'required|unique:permissions',
    ]);
    // Data Store
    Permission::create([
      'name'    => $request -> name,
      'slug'    => Str::slug($request -> name)
    ]);

    return back() -> with('success', 'permission added successfully');
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
    // sort the data in latest order
    $permissions = Permission::latest() -> get();

    $per = Permission::findOrFail($id);

    // return the get data
    return view('admin.pages.user.permission.index', [
      'all_permission'    => $permissions,
      'form_type'         => 'edit',
      'edit'              => $per
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id) {
    // get the data to update
    $update_data = Permission::findOrFail($id);

    // update data
    $update_data -> update([
      'name'    => $request -> name,
      'slug'    => Str::slug($request -> name)
    ]);

    // return with a success message
    return back() -> with('success', 'permission updated successfully');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id) {
    // search id to delete
    $data = Permission::findOrFail($id);

    // delete if found
    $data -> delete();

    // return with a success message
    return back() -> with('success-main', 'permission deleted successfully');
  }
}
