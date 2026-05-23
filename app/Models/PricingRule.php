<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingRule extends Model
{
    protected $fillable = [
        'room_type_id',
        'pricing_type',
        'adjustment_type',
        'adjustment_value',
        'start_date',
        'end_date',
        'is_active',
    ];

    // Relationships
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
}