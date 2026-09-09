<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityExclude extends Model
{
    protected $guarded = ['id'];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}