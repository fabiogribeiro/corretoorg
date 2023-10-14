<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}