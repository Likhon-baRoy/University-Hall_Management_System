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

    $seats = Seat::with(['room.hall'])->latest()->get();

    $rooms = Room::active()->latest()->get();

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
      'room_id' => [
        'required',
        'exists:rooms,id',
        function ($attribute, $value, $fail) {
          $room = Room::with('hall')->find($value);
          if (!$room || !$room->status || $room->deleted_at) {
            $fail('Cannot create seats in an inactive or deleted room.');
          }
          if (!$room->hall->status || $room->hall->deleted_at) {
            $fail('Cannot create seats in a room whose hall is inactive or deleted.');
          }
        },
      ],
      'start' => 'required|integer|min:1',
      'end' => 'required|integer|gte:start',
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
  // In HallSeatController.php, modify the edit method:
  public function edit(string $id)
  {
    $seat = Seat::findOrFail($id);
    $rooms = Room::active()->latest()->get();
    /* $rooms = Room::with('hall')
     *              ->where('status', true)
     *              ->whereNull('deleted_at')
     *              ->latest()
     *              ->get(); */

    // Return the new dedicated edit view
    return view('admin.pages.seat.edit', [
      'seat' => $seat,
      'rooms' => $rooms
    ]);
  }

  public function update(Request $request, string $id)
  {
    $request->validate([
      'room_id' => 'required|exists:rooms,id',
      'name' => 'required|string',
      'status' => 'required|boolean'
    ]);

    try {
      $seat = Seat::findOrFail($id);

      $seat->update([
        'room_id' => $request->room_id,
        'name' => $request->name,
        'status' => $request->status
      ]);

      return redirect()
            ->route('hall-seat.index')
            ->with('success', 'Seat updated successfully');

    } catch (\Exception $e) {
      return back()
            ->withInput()
            ->with('error', 'Failed to update seat: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id) {

    $seat_data = Seat::findOrFail($id);

    $seat_data -> delete();

    // return with a success message
    return back() -> with('success-main', $seat_data -> seat . ', deleted permanantly');
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
