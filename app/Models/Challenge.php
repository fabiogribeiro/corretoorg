<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class Challenge extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'body'
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}