<?php

namespace Database\Factories;

use App\Models\Challenge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'statement' => fake()->sentence(),
            'answer_data' => [
                'answer' => 'B',
                'type' => fake()->randomElement(['multiple-choice', 'expression', 'show']),
                'options' => ['A', 'B', 'C', 'D'],
            ],
        ];
    }
}
