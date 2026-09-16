<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pergunta extends Model
{
    // ... resto do model (fillable, etc.)

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
