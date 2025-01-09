<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use App\Models\Room;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class HallBookingController extends Controller
{
  /**
   * Get all hall booking details
   * @return View
   */
  public function index(): View
  {
    $rooms = Room::active()
                 ->with(['seats' => function($query) {
                   $query->where('status', true);
                 }])
                 ->whereHas('seats', function($query) {
                   $query->where('status', true);
                 })
                 ->paginate(8);

    $halls = Hall::where('status', true)
                 ->whereNull('deleted_at')
                 ->orderBy('name')
                 ->get();

    return view('frontend.pages.book', compact('rooms', 'halls'));
  }

  public function booking(?string $id)
  {
    $room = Room::with(['hall', 'seats' => function($query) {
      $query->where('status', true);
    }])->findOrFail($id);

    $bookingData = [
      'hall' => $room->hall->name,
      'room_id' => $room->id,
      'room' => $room->name,
      'available_seats' => $room->seats->pluck('number')->toArray()
    ];

    return view('admin.pages.register', compact('bookingData'));
  }
}
