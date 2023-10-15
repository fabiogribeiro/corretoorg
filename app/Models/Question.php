<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Challenge;

class Question extends Model
{
    protected $fillable = [
        'statement',
        'answer'
    ];

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }
}
