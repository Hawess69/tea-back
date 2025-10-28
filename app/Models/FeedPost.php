<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class FeedPost extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'image_url',
        'upvotes',
        'downvotes',
        'comments_count',
    ];

    protected $casts = [
        'upvotes' => 'integer',
        'downvotes' => 'integer',
        'comments_count' => 'integer',
    ];

    /**
     * Get the user that owns the feed post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the votes for the feed post.
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    /**
     * Get the comments for the feed post.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the upvotes count attribute.
     */
    public function getUpvotesCountAttribute(): int
    {
        return $this->votes()->where('vote_type', 'up')->count();
    }

    /**
     * Get the downvotes count attribute.
     */
    public function getDownvotesCountAttribute(): int
    {
        return $this->votes()->where('vote_type', 'down')->count();
    }
}
