<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\WalkInBooking;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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
            'Romantic Dinner' => 1700,
        ];

        return view('pages.chms-features.booking-management.create-booking', compact('rooms', 'ambiance', 'food_package'));
    }

    public function store(Request $request)
    {
        $ambiancePrices = [
            'Regular Room' => 0,
            'Cozy Ambiance' => 500,
            'Romantic Ambiance' => 1000,
        ];

        $foodPackagePrices = [
            'No Food Package' => 0,
            'Cozy Dinner for Family' => 1500,
            'Romantic Dinner' => 1700,
        ];

        $data = $request->validate([
            'room_id' => ['required', Rule::exists('rooms', 'id')->where('status', 'Available')],
            'fullname' => 'required|string|max:255',
            'phone_number' => ['required', 'string', 'max:13', 'regex:/^(?:09\d{9}|\+63\d{10})$/'],
            'ambiance' => ['required', Rule::in(array_keys($ambiancePrices))],
            'food_package' => ['required', Rule::in(array_keys($foodPackagePrices))],
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'remarks' => 'nullable|string|max:500',
        ]);

        $room = Room::where('status', 'Available')->findOrFail($data['room_id']);
        $nights = \Illuminate\Support\Carbon::parse($data['check_in'])
            ->diffInDays(\Illuminate\Support\Carbon::parse($data['check_out']));
        $data['room_price'] = $room->base_price * $nights;
        $data['micro_pricing_amount'] = $ambiancePrices[$data['ambiance']] + $foodPackagePrices[$data['food_package']];
        $data['total_price'] = $data['room_price'] + $data['micro_pricing_amount'];

        $data['status'] = 'Checked In';
        $data['reference_number'] = 'WB-' . strtoupper(Str::random(8));

        WalkInBooking::create($data);

        $room->status = 'Occupied';
        $room->save();

        return redirect()->route('booking.pending')->with('success', 'Walk-in booking created successfully.');
    }
}
