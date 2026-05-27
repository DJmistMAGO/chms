<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create()
   {

        return view('pages.chms-features.booking-management.create-booking');
   }
}
