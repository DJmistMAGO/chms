<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'base_price',
        'max_capacity',
    ];

    // Relationships
    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_type_id');
    }
}
