<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guide extends Authenticatable
{
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'guide_id');
    }
}