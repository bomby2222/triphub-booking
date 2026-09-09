<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'badge_text',
        'min_spend',
        'max_discount',
        'quota',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'discount_value' => 'float',
        'min_spend'      => 'float',
        'max_discount'   => 'float',
        'quota'          => 'integer',
        'used_count'     => 'integer',
        'start_date'     => 'date',
        'end_date'       => 'date',
    ];
}