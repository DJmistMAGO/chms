<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdVerification extends Model
{
    protected $fillable = [
        'user_id', 'valid_id_status', 'verified_by', 'verified_at', 'remarks',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
