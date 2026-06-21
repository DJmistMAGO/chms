<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index($referenceNumber = null)
    {
        $user = Auth::user();
        $userId = $user ? $user->getKey() : null;
        $bookings = Booking::where('user_id', $userId)
            ->whereIn('status', ['Pending', 'Confirmed', 'Verified', 'Expired', 'Canceled'])
            ->get();

        $rooms = Room::paginate(8);
        $allBookings = Booking::all();
        $pendingBookings = Booking::where('status', 'Pending')->get();
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'Available')->count();

        $bookingStats = [
            'Active' => $bookings->whereIn('status', ['Confirmed', 'Verified'])->count(),
            'Done' => $bookings->where('status', 'Expired')->count(),
            'Pending' => $bookings->where('status', 'Pending')->count(),
        ];

        return view('pages.dashboard.dashboard', [
            'title' => 'Caree Hotel',
            'referenceNumber' => $referenceNumber,
            'bookings' => $bookings,
            'rooms' => $rooms,
            'allBookings' => $allBookings,
            'pendingBookings' => $pendingBookings,
            'totalRooms' => $totalRooms,
            'bookingStats' => $bookingStats,
            'availableRooms' => $availableRooms,
        ]);
    }
}