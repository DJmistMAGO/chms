<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;


    protected $fillable = [
        'name',
        'email',
        'google_id',
        'password',
        'is_google_user',
        'has_changed_password',
        'first_google_login_at',
        'phone',
        'address',
        'avatar',
        'valid_id',
    ];


    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
        'valid_id',
    ];

    protected $casts = [
        'first_google_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        return $initials;
    }

        // Check if user has a valid ID
    public function hasValidID(){
        return !is_null($this->valid_id);
    }

}
