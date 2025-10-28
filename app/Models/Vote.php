<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Vote extends Model
{
    protected $fillable = [
        'voteable_id',
        'voteable_type',
        'user_id',
        'vote_type',
    ];

    /**
     * Get the voteable model (feed post or men post).
     */
    public function voteable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the vote.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
