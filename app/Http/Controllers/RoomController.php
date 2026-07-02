<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();

        return view('pages.chms-features.room-management.index', compact('rooms'));
    }

    public function updateStatus(Request $request, $room)
    {
        $request->validate([
            'status' => 'required|in:Available,Occupied,Maintenance,Reserved',
        ]);

        $room = Room::findOrFail($room);
        $room->status = $request->status;
        $room->save();

        return redirect()->route('room.index')->with('success', 'Room status updated successfully.');
    }
}