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

    /**
     * Get the votes for the men post.
     * Note: Men posts don't have votes in the current system.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'post_id')->whereRaw('1 = 0'); // Empty relationship
    }

    /**
     * Get upvotes count for men post.
     */
    public function getUpvotesCountAttribute(): int
    {
        return 0; // Men posts don't have votes
    }

    /**
     * Get downvotes count for men post.
     */
    public function getDownvotesCountAttribute(): int
    {
        return 0; // Men posts don't have votes
    }
}
