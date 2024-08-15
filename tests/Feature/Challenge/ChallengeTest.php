<?php

use App\Models\User;
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
