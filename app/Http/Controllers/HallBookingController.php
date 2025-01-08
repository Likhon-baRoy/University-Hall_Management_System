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
    // Add this at the start of the index method in HallBookingController
    $testRoom = Room::first();
    if ($testRoom) {
      \Log::info('Room photo path: ' . $testRoom->photo);
      \Log::info('Storage exists: ' . Storage::disk('public')->exists('image/room/' . $testRoom->photo));
      \Log::info('Full storage path: ' . Storage::disk('public')->path('image/room/' . $testRoom->photo));
    }

    // Get rooms with available seats, grouped by room
    $rooms = Room::query()
                 ->with(['hall', 'seats' => function($query) {
                   $query->where('status', true); // Only available seats
                 }])
                 ->whereHas('seats', function($query) {
                   $query->where('status', true); // Only rooms with available seats
                 })
                 ->paginate(8);

    // Get all halls for filter
    $halls = Hall::where('status', true)
                 ->orderBy('name')
                 ->get();

    return view('frontend.pages.book', compact('rooms', 'halls'));
  }

  public function booking(?string $id)
  {
    $room = Room::with(['hall', 'seats' => function($query) {
      $query->where('status', true);
    }])->findOrFail($id);

    return view('frontend.pages.booking', compact('room'));
  }
}
