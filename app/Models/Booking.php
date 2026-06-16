<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'reference_number',
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
        'expires_at',
        'verified_by',
        'verified_at',
    ];

    protected $dates = [
        'check_in_date',
        'check_out_date',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'number_of_guests' => 'integer',
        'room_price' => 'decimal:2',
        'micro_pricing_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'check_in'   => 'date',
        'check_out'  => 'date',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];



    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
