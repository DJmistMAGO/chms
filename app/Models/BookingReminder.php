<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;

class BookingReminder extends Model
{


    protected $fillable = ['booking_id', 'type', 'sent_at'];
    protected $casts = ['sent_at' => 'datetime'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
