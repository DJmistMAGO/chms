<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Booking;

class MicroPricingController extends Controller
{

    public function booking($roomType)
    {


        $rooms = [

            'standard' => [
                'name' => 'Standard Room',
                'price' => 1500,
                'total_rooms' => 14,
            ],

            'standard-premium' => [
                'name' => 'Standard Premium Room',
                'price' => 1900,
                'total_rooms' => 10,
            ],

            'family' => [
                'name' => 'Family Room',
                'price' => 2700,
                'total_rooms' => 4,
            ],
        ];



        if (!isset($rooms[$roomType])) {
            abort(404);
        }



        $selectedRoom = $rooms[$roomType];

        $roomName = $selectedRoom['name'];

        $price = $selectedRoom['price'];



        $totalRooms = $selectedRoom['total_rooms'];



        $bookings = Booking::where('room_type', $roomName)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        

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
