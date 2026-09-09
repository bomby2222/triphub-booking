<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $guarded = ['id'];

    public function schedules(): HasMany
    {
        return $this->hasMany(ActivitySchedule::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(ActivityItinerary::class)->orderBy('sort_order');
    }

    public function includes(): HasMany
    {
        return $this->hasMany(ActivityInclude::class);
    }

    public function excludes(): HasMany
    {
        return $this->hasMany(ActivityExclude::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_hidden', false);
    }
}