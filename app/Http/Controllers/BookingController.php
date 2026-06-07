<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\User;

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
        ->get();

        $confirmedBookings = Booking::where('user_id', $user->id)
        ->where('status', 'confirmed')
        ->get();

        return view('pages.chms-features.my-reservations.reservation', compact('pendingBookings', 'confirmedBookings'));
    }

    public function confirmed()
    {
        return view('pages.chms-features.booking-management.confirmed-booking');
    }

    public function cancelled()
    {
        return view('pages.chms-features.booking-management.cancelled-booking');
    }
}
