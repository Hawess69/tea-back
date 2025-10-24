<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'vote_type',
    ];

    /**
     * Get the feed post that owns the vote.
     */
    public function feedPost(): BelongsTo
    {
        return $this->belongsTo(FeedPost::class, 'post_id');
    }

    /**
     * Get the user that owns the vote.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
