<?php

use App\Models\User;
use App\Models\Challenge;
use App\Models\Question;
use Livewire\Volt\Volt;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('admin can create challenge', function () {
    Volt::test('layout.navigation')
        ->assertSee(__('Log in'))
        ->assertDontSee(__('Create challenge'));

    $this->actingAs(User::factory()->create(['id' => 1]));

    Volt::test('layout.navigation')->assertSee(__('Create challenge'));

    Volt::test('challenges.create')
        ->set('title', 'title')
        ->set('subject', 'subject')
        ->call('submitForm')
        ->assertRedirect();
});

test('unprivileged user can\'t create challenge', function () {
    $this->actingAs(User::factory()->create(['id' => 2]));

    Volt::test('layout.navigation')->assertDontSee(__('Create challenge'));
    $this->get(route('challenges.create'))->assertForbidden();
});

test('user can solve a question', function () {
    $this->actingAs(User::factory()->create(['id' => 1]));

    $challenge = Challenge::factory()->create();
    $question = Question::factory()->for($challenge)->create();

    Volt::test('challenges.question-'.$question->type, ['question' => $question, 'challenge' => $challenge])
        ->call('submitForm')
        ->assertSet('solved', false)
        ->set('answer', $question->answer)->call('submitForm')
        ->assertSet('solved', true);
});

test('user can solve multiple choice question (singular)', function () {
    $this->actingAs(User::factory()->create(['id' => 1]));

    $challenge = Challenge::factory()->create();
    $question = Question::factory()->for($challenge)->create(['answer_data' => [
        'answer' => 'A',
        'type' => 'multiple-choice',
        'options' => ["A", "B", "C", "D"]
    ]]);

    Volt::test('challenges.question-multiple-choice', ['question' => $question, 'challenge' => $challenge])
        ->set('answer', 'B')->call('submitForm')
        ->assertSet('solved', false)
        ->set('answer', 'A')->call('submitForm')
        ->assertSet('solved', true);
});

test('user can solve multiple choice question (checkbox)', function () {
    $this->actingAs(User::factory()->create(['id' => 1]));

    $challenge = Challenge::factory()->create();
    $question = Question::factory()->for($challenge)->create(['answer_data' => [
        'answer' => 'B;C',
        'type' => 'multiple-choice',
        'options' => ["A", "B", "C", "D"]
    ]]);

    Volt::test('challenges.question-multiple-choice', ['question' => $question, 'challenge' => $challenge])
        ->set('answer', 'B;C')->call('submitForm')
        ->assertSet('solved', false)
        ->set('checkAnswer', ['B', 'C', 'D'])->call('submitForm')
        ->assertSet('solved', false)
        ->set('checkAnswer', ['B'])->call('submitForm')
        ->assertSet('solved', false)
        ->set('checkAnswer', ['B', 'C'])->call('submitForm')
        ->assertSet('solved', true);
});

test('user can solve expression question', function () {
    $this->actingAs(User::factory()->create(['id' => 1]));

    $challenge = Challenge::factory()->create();
    $question = Question::factory()->for($challenge)->create(['answer_data' => [
        'answer' => 'x^2',
        'type' => 'expression',
    ]]);

    Volt::test('challenges.question-expression', ['question' => $question, 'challenge' => $challenge])
        ->set('answer', 'x')->call('submitForm')
        ->assertSet('solved', false)
        ->set('answer', 'x^2 + 0')->call('submitForm')
        ->assertSet('solved', true);
});
