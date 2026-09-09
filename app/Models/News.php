<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'category',
        'badge_text',
        'content',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}