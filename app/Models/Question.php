<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Models\Challenge;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['statement', 'explanation', 'answer_data'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'answer_data' => AsArrayObject::class
    ];

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    public function answer(): Attribute
    {
        return new Attribute(
            get: fn () => $this->answer_data['answer'],
            set: fn (string $value) => $this->answer_data['answer'] = $value
        );
    }

    public function type(): Attribute
    {
        return new Attribute(
            get: fn () => $this->answer_data['type'],
            set: fn (string $value) => $this->answer_data['type'] = $value
        );
    }

    public function template(): Attribute
    {
        return new Attribute(
            get: fn () => $this->answer_data['template'],
            set: fn (string $value) => $this->answer_data['template'] = $value
        );
    }

    public function options(): Attribute
    {
        return new Attribute(
            get: fn () => $this->answer_data['options'] ?: ["A", "B", "C", "D"],
            set: fn ($values) => $this->answer_data['options'] = $values
        );
    }
}
