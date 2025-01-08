<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Hall;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HallRoomController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index() {
    $rooms = Room::latest()->get();
    $halls = Hall::where('status', true)
                 ->whereNull('deleted_at')
                 ->latest()
                 ->get();

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
    $request->validate([
      'hall_id' => 'required|exists:halls,id',
      'start' => 'required|integer|min:1',
      'end' => 'required|integer|gte:start',
    ]);

    try {
      DB::transaction(function() use ($request) {
        $hall = Hall::findOrFail($request->hall_id);

        // Create room collection
        $rooms = collect(range($request->start, $request->end))
                                       ->map(function($number) use ($hall) {
                                         return [
                                           'hall_id' => $hall->id,
                                           'name' => $number,
                                           'created_at' => now(),
                                           'updated_at' => now()
                                         ];
                                       });

        // Insert in chunks to prevent memory issues with large ranges
        foreach ($rooms->chunk(100) as $chunk) {
          Room::insert($chunk->toArray());
        }
      });

      return back()->with('success', 'Rooms added successfully');
    } catch (\Exception $e) {
      return back()->with('error', 'Failed to create rooms: ' . $e->getMessage());
    }
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
    $rooms = Room::latest()->get();
    $halls = Hall::where('status', true)
                 ->whereNull('deleted_at')
                 ->latest()
                 ->get();

    return view('admin.pages.room.index', [
      'form_type' => 'edit',
      'room' => $room,
      'rooms' => $rooms,
      'halls' => $halls
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $request->validate([
      'hall_id' => 'required|exists:halls,id',
      'name' => 'required|string',
    ]);

    $room = Room::findOrFail($id);

    $room->update([
      'hall_id' => $request->hall_id,
      'name' => $request->name,
    ]);

    return back()->with('success', 'Room updated successfully');
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
   * Display a listing of the trashed rooms.
   */
  public function trash()
  {
    $rooms = Room::onlyTrashed()->latest()->get();
    $halls = Hall::latest()->get();

    return view('admin.pages.room.trash', [
      'rooms' => $rooms,
      'halls' => $halls
    ]);
  }

  /**
   * Soft delete the specified room.
   */
  public function destroy(string $id)
  {
    $room = Room::findOrFail($id);
    $room->delete(); // This will soft delete

    return back()->with('success-main', 'Room moved to trash successfully');
  }

  /**
   * Restore the specified room from trash.
   */
  public function restore($id)
  {
    $room = Room::onlyTrashed()->findOrFail($id);
    $room->restore();

    return back()->with('success-main', 'Room restored successfully');
  }

  /**
   * Permanently delete the specified room.
   */
  public function forceDelete($id)
  {
    $room = Room::onlyTrashed()->findOrFail($id);
    $room->forceDelete();

    return back()->with('success-main', 'Room permanently deleted');
  }
}
