<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['message'])]
class Chirp extends Model
{
    /**
     * Get the user that owns the chirp.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
