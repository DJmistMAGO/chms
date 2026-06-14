<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_no',
        'room_type',
        'floor',
        'base_price',
        'status',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

}
