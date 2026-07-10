<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalkInBooking extends Model
{
    protected $fillable = [
        'room_id',
        'fullname',
        'phone_number',
        'room_type',
        'floor_level',
        'ambiance',
        'food_package',
        'check_in',
        'check_out',
        'number_of_guests',
        'room_price',
        'micro_pricing_amount',
        'total_price',
        'status',
        'remarks'
    ];

    protected $dates = [
        'check_in',
        'check_out',
    ];

    protected $casts = [
        'number_of_guests' => 'integer',
        'room_price' => 'decimal:2',
        'micro_pricing_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'check_in'   => 'date',
        'check_out'  => 'date',
    ];

    

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
