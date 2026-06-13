<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create()
    {

        return view('pages.chms-features.booking-management.create-booking');
    }

    public function myReservations()
    {
    $user = auth()->user();

    $pendingBookings = Booking::where('user_id', $user->id)
        ->where('status', 'pending')
        ->get()
        ->transform(function ($booking) {
            $booking->check_in = Carbon::parse($booking->check_in);
            $booking->check_out = Carbon::parse($booking->check_out);
            return $booking;
        });

    $confirmedBookings = Booking::where('user_id', $user->id)
        ->where('status', 'confirmed')
        ->get()
        ->transform(function ($booking) {
            $booking->check_in = Carbon::parse($booking->check_in);
            $booking->check_out = Carbon::parse($booking->check_out);
            return $booking;
        });

    return view('pages.chms-features.my-reservations.reservation', compact('pendingBookings', 'confirmedBookings'));
    }

    public function pending()
    {
        $pendingBookings = Booking::where('status', 'pending')
        ->latest()
        ->paginate(15);

        return view('pages.chms-features.booking-management.pending-booking', compact('pendingBookings'));
    }

    public function confirmed()
    {
        // Fetch all confirmed bookings for the staff
        $confirmedBookings = Booking::where('status', 'confirmed')->get();

        return view('pages.chms-features.booking-management.confirmed-booking', compact('confirmedBookings'));
    }

    public function cancelled()
    {
        // Fetch all cancelled bookings for the staff
        $cancelledBookings = Booking::where('status', 'cancelled')->get();

        return view('pages.chms-features.booking-management.cancelled-booking', compact('cancelledBookings'));
    }
}
