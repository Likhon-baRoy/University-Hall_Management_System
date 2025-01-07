<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class HallBookingController extends Controller
{
    /**
     * Get all hall booking details
     * @return View
     */
    public function index(): View
    {
        $seats = Seat::query()
            ->where('status', true)
            ->with(['room' => fn($query) => $query->with('hall')])
            ->get();
        return view('frontend.pages.book', compact('seats'));
    }

    public function booking(?string $id)
    {
        return $id;
    }
}
