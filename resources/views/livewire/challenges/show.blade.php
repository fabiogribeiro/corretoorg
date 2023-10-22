<?php

use Livewire\Volt\Component;
use App\Models\Challenge;
use App\Models\Question;
use Illuminate\Support\Str;

new class extends Component
{
    public Challenge $challenge;
} ?>

<div>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ $challenge->title }}
    </h2>
    <div class="mt-6">
        <x-mmd>{{ $challenge->body }}</x-mmd>
    </div>
    <div class="mt-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Questions') }}
        </h2>
        @foreach ($challenge->questions as $question)
            <livewire:challenges.question :challenge="$challenge" :question="$question" />
        @endforeach
    </div>
</div>
