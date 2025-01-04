<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Seat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HallSeatController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index() {

    /*     $seats = Seat::latest() -> where('trash', false) -> get(); */
    $seats = Seat::latest() -> get();

    $rooms = Room::latest()->with('hall') -> get();

    return view('admin.pages.seat.index', [
      'form_type' => 'create',
      'seats'   => $seats,
      'rooms'   => $rooms
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(Room $room)
  {

  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // Validation
    $request->validate([
      'room_id' => 'required|exists:rooms,id',
      'start'   => 'required|integer|min:1',
      'end'     => 'required|integer|gte:start',
    ]);

    // Prepare data for bulk insertion
    $response = [];
    foreach (range($request->start, $request->end) as $seatNumber) {
      $response[] = [
        'room_id'    => $request->room_id,
        'name'       => $seatNumber,
        'created_at' => now(),
        'updated_at' => now(),
      ];
    }

    // Insert seats into database
    Seat::insert($response);

    // Redirect back with success message
    return back()->with('success', 'Seats added successfully.');
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

    $seat = Seat::findOrFail($id);

    $seats = Seat::latest() -> get();

    return view('admin.pages.seat.index', [
      'form_type' => 'edit',
      'seats'   => $seats,
      'seat'    => $seat,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    // get seat
    $seat = Seat::findOrFail($id);

    // update seat
    $seat -> update([
      'name'             => $request -> name,
    ]);

    // return back
    return back() -> with('success' , 'Seat updated successful');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id) {

    $seat_data = Seat::findOrFail($id);

    $seat_data -> delete();

    // return with a success message
    return back() -> with('success-main', $data -> seat . ', deleted permanantly');
  }


  /*****************************************************************
   * Custom Methods Section
   *****************************************************************/
  /**
   * Status update
   */
  public function updateStatus($id) {

    $seat_data = Seat::findOrfail($id);

    if ($seat_data -> status) {

      $seat_data -> update([
        'status'    => false
      ]);

    } else{

      $seat_data -> update([
        'status'    => true
      ]);
    }

    return back() -> with('success-main', $seat_data -> seat . ', status update successful');
  }

  /**
   * Trash update
   */
  /* public function updateTrash($id) {

   *   $seat_data = Seat::findOrfail($id);

   *   if ($seat_data -> trash) {

   *     $seat_data -> update([
   *       'trash'    => false
   *     ]);

   *   } else{

   *     $seat_data -> update([
   *       'trash'    => true
   *     ]);
   *   }

   *   // return with a success message
   *   return back() -> with('success-main', $seat_data -> seat . ' data moved to Trash');
   * } */

  /**
   * Display Trash Users
   */
  /* public function trashSeat() {

   *   $seat_data = Seat::latest() -> where('trash', true) -> get();

   *   return view('admin.pages.seat.trash', [
   *     'seat_data'      => $seat_data,
   *     'form_type'     => 'trash',
   *   ]);
   * } */
}
