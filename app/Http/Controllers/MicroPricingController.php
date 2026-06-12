<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Booking;

class MicroPricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function booking($roomType)
    {
        /*
    ROOM CONFIGURATION
    */

        $rooms = [

            'standard' => [
                'name' => 'Standard Room',
                'price' => 1500,
                'room_numbers' => [
                    'S101',
                    'S102',
                    'S103',
                    'S104',
                    'S105',
                    'S106',
                    'S107',
                    'S201',
                    'S202',
                    'S401',
                    'S402',
                    'S403',
                    'S404',
                    'S405',
                ],
            ],

            'standard-premium' => [
                'name' => 'Standard Premium Room',
                'price' => 1900,
                'room_numbers' => [
                    'SP101',
                    'SP102',
                    'SP201',
                    'SP202',
                    'SP401',
                    'SP402',
                    'SP403',
                    'SP404',
                    'SP405',
                    'SP406',
                ],
            ],

            'family' => [
                'name' => 'Family Room',
                'price' => 2700,
                'room_numbers' => [
                    'F201',
                    'F202',
                    'F203',
                    'F204',
                ],
            ],
        ];

        /*
    CHECK ROOM TYPE
    */

        if (!isset($rooms[$roomType])) {
            abort(404);
        }

        /*
    SELECTED ROOM
    */

        $selectedRoom = $rooms[$roomType];

        $roomName = $selectedRoom['name'];

        $price = $selectedRoom['price'];

        /*
    TOTAL AVAILABLE ROOMS
    */

        $totalRooms = count($selectedRoom['room_numbers']);

        /*
    GET BOOKINGS
    */

        $bookings = Booking::where('room_type', $roomName)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        /*
    COUNT BOOKINGS PER DATE
    */

        $dateCounts = [];

        foreach ($bookings as $booking) {

            $start = Carbon::parse($booking->check_in)->startOfDay();

            $end = Carbon::parse($booking->check_out)
                ->startOfDay()
                ->subDay();

            $period = CarbonPeriod::create($start, $end);

            foreach ($period as $date) {

                $formatted = $date->format('Y-m-d');

                if (!isset($dateCounts[$formatted])) {
                    $dateCounts[$formatted] = 0;
                }

                $dateCounts[$formatted]++;
            }
        }

        /*
    DISABLE DATES ONLY WHEN FULLY BOOKED
    */

        $disabledDates = [];

        foreach ($dateCounts as $date => $count) {

            if ($count >= $totalRooms) {

                $disabledDates[] = $date;
            }
        }


        return view('micro-pricing', compact(
            'roomName',
            'price',
            'disabledDates'
        ));
    }



    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
