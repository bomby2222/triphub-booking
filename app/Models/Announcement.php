<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'publish_date' => 'date',
        'expire_date' => 'date',
        'is_published' => 'boolean',
    ];
}