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
     * Set the tags attribute.
     */
    public function setTagsAttribute($value): void
    {
        if (is_null($value)) {
            $this->attributes['tags'] = json_encode([]);
        } elseif (is_string($value)) {
            $tags = array_map('trim', explode(',', $value));
            $tags = array_filter($tags); // Remove empty values
            $this->attributes['tags'] = json_encode($tags);
        } else {
            $this->attributes['tags'] = json_encode($value);
        }
    }

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
    public function flags(): MorphMany
    {
        return $this->morphMany(Flag::class, 'flagable');
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
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    /**
     * Get upvotes count for men post.
     */
    public function getUpvotesCountAttribute(): int
    {
        return $this->votes()->where('vote_type', 'up')->count();
    }

    /**
     * Get downvotes count for men post.
     */
    public function getDownvotesCountAttribute(): int
    {
        return $this->votes()->where('vote_type', 'down')->count();
    }
}
