<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\WalkInBooking;

class WalkInBookingController extends Controller
{

    public function create()
    {
        $rooms = Room::where('status', 'Available')->get();

        //ambiance type and price in array
        $ambiance = [
            'Regular Room' => 0,
            'Cozy Ambiance' => 500,
            'Romantic Ambiance' => 1000,
        ];

        $food_package = [
            'No Food Package' => 0,
            'Cozy Dinner for Family' => 1500,
            'Romantic Dinner' => 1500,
        ];

        return view('pages.chms-features.booking-management.create-booking', compact('rooms', 'ambiance', 'food_package'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'fullname' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'ambiance' => 'required|string|max:255',
            'food_package' => 'required|string|max:255',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'room_price' => 'required|numeric|min:0',
            'micro_pricing_amount' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
        ]);

        $data['status'] = 'Checked In';

        WalkInBooking::create($data);

        $room_id = $data['room_id'];
        $room = Room::find($room_id);
        $room->status = 'Occupied';
        $room->save();

        return redirect()->route('booking.checkin')->with('success', 'Walk-in booking created successfully.');
    }
}
