<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Flag extends Model
{
    protected $fillable = [
        'flagable_id',
        'flagable_type',
        'user_id',
        'flag_type',
    ];

    /**
     * Get the flagable model (feed post or men post).
     */
    public function flagable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the flag.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
