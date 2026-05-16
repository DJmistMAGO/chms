<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MicroPricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function booking($roomType)
    {
        if($roomType == 'standard') {
            $roomType = 'Standard Room';
            $price = 1500;
        } elseif($roomType == 'standard-premium') {
            $roomType = 'Standard Premium Room';
            $price = 2000;
        } elseif($roomType == 'family') {
            $roomType = 'Family Room';
            $price = 2500;
        } else {
            abort(404);
        }

        return view('micro-pricing', compact('roomType', 'price'));
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
