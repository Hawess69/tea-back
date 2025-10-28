<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsDaily extends Model
{
    protected $fillable = [
        'date',
        'new_users',
        'feed_posts',
        'men_posts',
        'red_flags',
        'green_flags',
        'total_comments',
        'top_cities',
    ];

    protected $casts = [
        'date' => 'date',
        'top_cities' => 'array',
    ];
}
