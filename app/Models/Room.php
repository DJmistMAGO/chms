<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_type',
        'capacity',
        'price_per_night',
        'room_number',
        'is_available',
        'description',
        'image',
        'amenities',
        'floor',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id');
    }

    


}
