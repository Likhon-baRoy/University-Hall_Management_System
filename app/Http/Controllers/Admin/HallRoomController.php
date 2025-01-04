<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Hall;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HallRoomController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index() {

    /*     $rooms = Room::latest() -> where('trash', false) -> get(); */
    $rooms = Room::latest() -> get();

    $halls = Hall::latest() -> get();

    return view('admin.pages.room.index', [
      'form_type' => 'create',
      'rooms'   => $rooms,
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
      'hall_id'            => 'required',
    ]);

    foreach(range($request->start, $request->end) as $item) {

      $response[] = [
        'hall_id'    => $request -> hall_id,
        'name'    => $item,
        'created_at' => now(),
        'updated_at' => now()
      ];

    }

    // add new room
    Room::insert($response);

    // return back
    return back() -> with('success' , 'Room Added successful');
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

    $room = Room::findOrFail($id);

    $rooms = Room::latest() -> get();

    return view('admin.pages.room.index', [
      'form_type' => 'edit',
      'rooms'   => $rooms,
      'room'    => $room,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    // get room
    $room = Room::findOrFail($id);

    // update room
    $room -> update([
      'name'             => $request -> name,
    ]);

    // return back
    return back() -> with('success' , 'Room updated successful');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id) {

    $room_data = Room::findOrFail($id);

    $room_data -> delete();

    // return with a success message
    return back() -> with('success-main', $data -> room . ', deleted permanantly');
  }


  /*****************************************************************
   * Custom Methods Section
   *****************************************************************/
  /**
   * Status update
   */
  public function updateStatus($id) {

    $room_data = Room::findOrfail($id);

    if ($room_data -> status) {

      $room_data -> update([
        'status'    => false
      ]);

    } else{

      $room_data -> update([
        'status'    => true
      ]);
    }

    return back() -> with('success-main', $room_data -> room . ', status update successful');
  }

  /**
   * Trash update
   */
  /* public function updateTrash($id) {

   *   $room_data = Room::findOrfail($id);

   *   if ($room_data -> trash) {

   *     $room_data -> update([
   *       'trash'    => false
   *     ]);

   *   } else{

   *     $room_data -> update([
   *       'trash'    => true
   *     ]);
   *   }

   *   // return with a success message
   *   return back() -> with('success-main', $room_data -> room . ' data moved to Trash');
   * } */

  /**
   * Display Trash Users
   */
  /* public function trashRoom() {

   *   $room_data = Room::latest() -> where('trash', true) -> get();

   *   return view('admin.pages.room.trash', [
   *     'room_data'      => $room_data,
   *     'form_type'     => 'trash',
   *   ]);
   * } */
}
