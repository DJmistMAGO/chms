<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        //get all rooms
        $rooms = Room::all();

        return view('pages.chms-features.room-management.index', compact('rooms'));
    }
}
