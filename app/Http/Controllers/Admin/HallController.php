<?php

namespace App\Http\Controllers\Admin;

use App\Models\Hall;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HallController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index() {

    /*     $halls = Hall::latest() -> where('trash', false) -> get(); */
    $halls = Hall::latest() -> get();

    return view('admin.pages.hall.index', [
      'form_type' => 'create',
      'halls'   => $halls
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
  public function store(Request $request)
  {
    // validation
    $request -> validate([
      'name'            => 'required',
      'gender'      => 'required',
      'location'        => 'required',
    ]);

    // add new hall
    Hall::create([
      'name'             => $request -> name,
      'gender'       => $request -> gender,
      'location'         => $request -> location,
    ]);

    // return back
    return back() -> with('success' , 'Hall Added successful');
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

    $hall = Hall::findOrFail($id);

    $halls = Hall::latest() -> get();

    return view('admin.pages.hall.index', [
      'form_type' => 'edit',
      'halls'   => $halls,
      'hall'    => $hall,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    // get hall
    $hall = Hall::findOrFail($id);

    // update hall
    $hall -> update([
      'name'             => $request -> name,
      'gender'       => $request -> gender,
      'location'         => $request -> location,
    ]);

    // return back
    return back() -> with('success' , 'Hall updated successful');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id) {

    $hall_data = Hall::findOrFail($id);

    $hall_data -> delete();

    // return with a success message
    return back() -> with('success-main', $data -> hall . ', deleted permanantly');
  }


  /*****************************************************************
   * Custom Methods Section
   *****************************************************************/
  /**
   * Status update
   */
  public function updateStatus($id) {

    $hall_data = Hall::findOrfail($id);

    if ($hall_data -> status) {

      $hall_data -> update([
        'status'    => false
      ]);

    } else{

      $hall_data -> update([
        'status'    => true
      ]);
    }

    return back() -> with('success-main', $hall_data -> hall . ', status update successful');
  }

  /**
   * Trash update
   */
  /* public function updateTrash($id) {

   *   $hall_data = Hall::findOrfail($id);

   *   if ($hall_data -> trash) {

   *     $hall_data -> update([
   *       'trash'    => false
   *     ]);

   *   } else{

   *     $hall_data -> update([
   *       'trash'    => true
   *     ]);
   *   }

   *   // return with a success message
   *   return back() -> with('success-main', $hall_data -> hall . ' data moved to Trash');
   * } */

  /**
   * Display Trash Users
   */
  /* public function trashHall() {

   *   $hall_data = Hall::latest() -> where('trash', true) -> get();

   *   return view('admin.pages.hall.trash', [
   *     'hall_data'      => $hall_data,
   *     'form_type'     => 'trash',
   *   ]);
   * } */
}
