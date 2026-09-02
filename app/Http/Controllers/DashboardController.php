<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\WalkInBooking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index($referenceNumber = null)
    {
        $user = Auth::user();
        $userId = $user ? $user->getKey() : null;

        $bookings = Booking::where('user_id', $userId)
            ->whereIn('status', ['Pending', 'Confirmed', 'Verified', 'Expired', 'Canceled'])
            ->get()->sortByDesc('check_in');

        $bookingHistory = Booking::where('user_id', $userId)
            ->where('status', 'Verified')
            ->where('check_out', '<', now())
            ->orderByDesc('check_out')
            ->get();

        $rooms = Room::with(['currentBooking.user', 'currentWalkInBooking'])
            ->orderByRaw("CASE status
                WHEN 'Occupied' THEN 1
                WHEN 'Available' THEN 2
                WHEN 'Maintenance' THEN 3
                WHEN 'Reserved' THEN 4
                ELSE 5
            END")
            ->orderBy('floor')
            ->orderBy('room_no')
            ->paginate(7);
        $allBookings = Booking::all();
        $recentBookings = Booking::with('user')
            ->whereIn('status', ['Confirmed', 'Checked In', 'Completed'])
            ->latest('created_at')
            ->take(3)
            ->get();
        $bookingsToday = Booking::whereDate('created_at', today())->count()
            + WalkInBooking::whereDate('created_at', today())->count();
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
            'bookingHistory' => $bookingHistory,
            'rooms' => $rooms,
            'allBookings' => $allBookings,
            'recentBookings' => $recentBookings,
            'bookingsToday' => $bookingsToday,
            'pendingBookings' => $pendingBookings,
            'totalRooms' => $totalRooms,
            'bookingStats' => $bookingStats,
            'availableRooms' => $availableRooms,
        ]);
    }
}
