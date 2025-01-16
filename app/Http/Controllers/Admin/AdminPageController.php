<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Admin;
use App\Models\Hall;
use App\Models\Room;
use App\Models\Seat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPageController extends Controller
{
  // Show Admin Login page
  public function showDashboard() {
    // Get counts for the cards
    $data = [
      'total_students' => Admin::where('user_type', 'student')->count(),
      'total_staff' => Admin::whereIn('user_type', ['teacher', 'staff'])->count(),
      'total_users' => Admin::count(),
      'total_rooms' => Room::count(),
      'total_seats' => Seat::count(),
      'available_seats' => Seat::where('status', true)->count()
    ];

    // Data for Hall Occupancy Chart
    $data['hall_occupancy'] = Hall::with(['rooms.seats'])
                                  ->get()
                                  ->map(function ($hall) {
                                    $total_seats = 0;
                                    $occupied_seats = 0;

                                    foreach ($hall->rooms as $room) {
                                      $total_seats += $room->seats->count();
                                      $occupied_seats += $room->seats->where('status', false)->count();
                                    }

                                    return [
                                      'hall' => $hall->name,
                                      'total' => $total_seats,
                                      'occupied' => $occupied_seats
                                    ];
                                  });

    // Data for Resident Distribution Chart
    $data['resident_distribution'] = [
      ['label' => 'Students', 'value' => $data['total_students']],
      ['label' => 'Teachers', 'value' => Admin::where('user_type', 'teacher')->count()],
      ['label' => 'Staff', 'value' => Admin::where('user_type', 'staff')->count()]
    ];

    return view('admin.pages.dashboard', $data);
  }
}
