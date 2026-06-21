<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index($referenceNumber = null)
    {
        $bookings = [];

        if (Auth::check() && Auth::user()->role === 'staff') {
            $bookings = Booking::all();
            $rooms = Room::all();
        }

        // dd($bookings);

        return view('pages.dashboard.dashboard', [
            'title' => 'Caree Hotel',
            'referenceNumber' => $referenceNumber,
            'bookings' => $bookings,
            'rooms' => $rooms ?? null,
        ]);
    }
}