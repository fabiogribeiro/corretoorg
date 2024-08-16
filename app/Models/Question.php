<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Challenge;

class Question extends Model
{
    use HasFactory;

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

    /**
     * Dynamically retrieve commonly used Question attributes.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (in_array($key, ['answer', 'type', 'template', 'options'])) {
            return $this->answer_data[$key];
        }

        return parent::__get($key);
    }

    /**
     * Dynamically set commonly used Question attributes.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        if (in_array($key, ['answer', 'type', 'template', 'options'])) {
            $this->answer_data[$key] = $value;
            return;
        }

        parent::__set($key, $value);
    }
}
