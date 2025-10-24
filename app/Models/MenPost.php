<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class MenPost extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'city',
        'tags',
        'caption',
        'photo_url',
        'flag_counts',
    ];

    protected $casts = [
        'tags' => 'array',
        'flag_counts' => 'array',
    ];

    /**
     * Get the user that owns the men post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the flags for the men post.
     */
    public function flags(): HasMany
    {
        return $this->hasMany(Flag::class, 'post_id');
    }

    /**
     * Get the comments for the men post.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
