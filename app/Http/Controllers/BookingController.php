<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create()
    {

        return view('pages.chms-features.booking-management.create-booking');
    }

    public function myReservations()
    {
        return view('pages.chms-features.my-reservations.reservation');
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
