<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['currentBooking.user', 'currentWalkInBooking'])->get();

        return view('pages.chms-features.room-management.index', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_no'    => 'required|string|max:255|unique:rooms,room_no',
            'floor'      => 'required|in:1,2,4',
            'room_type'  => ['required', Rule::in(['Standard Room', 'Standard Premium Room', 'Family Room'])],
            'base_price' => 'required|numeric|min:0|max:99999999.99|decimal:0,2',
            'status'     => 'required|in:Available,Occupied,Maintenance,Reserved',
        ]);

        Room::create($request->only([
            'room_no',
            'floor',
            'room_type',
            'base_price',
            'status',
        ]));

        return redirect()->route('room.index')->with('success', 'Room added successfully.');
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

    public function update(Request $request, $room)
    {
        $room = Room::findOrFail($room);

        $request->validate([
            'room_no'    => ['required', 'string', 'max:255', Rule::unique('rooms', 'room_no')->ignore($room->id)],
            'floor'      => 'required|in:1,2,4',
            'base_price' => 'required|numeric|min:0|max:99999999.99|decimal:0,2',
            'status'     => 'required|in:Available,Occupied,Maintenance,Reserved',
        ]);

        $room->update($request->only([
            'room_no',
            'floor',
            'base_price',
            'status',
        ]));

        return redirect()->route('room.index')->with('success', 'Room updated successfully.');
    }

    public function destroy($room)
    {
        $room = Room::findOrFail($room);
        $room->delete();

        return redirect()->route('room.index')->with('success', 'Room deleted successfully.');
    }
}
