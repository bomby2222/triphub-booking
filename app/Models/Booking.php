<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Booking extends Model
{
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * รอบเดินทาง (รองรับทั้ง activity_schedule_id และ schedule_id)
     */
    public function schedule(): BelongsTo
    {
        $foreignKey = Schema::hasColumn('bookings', 'activity_schedule_id') 
            ? 'activity_schedule_id' 
            : 'schedule_id';

        return $this->belongsTo(ActivitySchedule::class, $foreignKey);
    }

    public function members(): HasMany
    {
        $memberModel = class_exists(BookingMember::class) ? BookingMember::class : Member::class;
        return $this->hasMany($memberModel, 'booking_id');
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class, 'guide_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }
}