<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flag extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'flag_type',
    ];

    /**
     * Get the men post that owns the flag.
     */
    public function menPost(): BelongsTo
    {
        return $this->belongsTo(MenPost::class, 'post_id');
    }

    /**
     * Get the user that owns the flag.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
