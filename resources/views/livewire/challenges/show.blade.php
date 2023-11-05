<?php

use Livewire\Volt\Component;
use App\Models\Challenge;
use App\Models\Question;
use Illuminate\Support\Str;

new class extends Component
{
    public Challenge $challenge;
    public $other_challenges;

    public function mount()
    {
        $this->other_challenges = Challenge::where('subject', $this->challenge->subject)->get();
    }
} ?>

<div class="flex space-x-1">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg w-1/3 h-min">
        <h2 class="text-2xl font-bold text-gray-700">{{ $challenge->subject }}</h2>
        <ul class="mt-6">
        @foreach($other_challenges as $ochallenge)
            <li class="flex">
            @if($ochallenge->id == $challenge->id)
                <span class="-ml-4 mr-2"><x-select-circle/></span>
            @elseif(in_array($ochallenge->id, auth()->user()->solved['challenges']))
                <span class="-ml-4 mr-2"><x-select-circle bg="bg-emerald-500"/></span>
            @else
                <span class="-ml-4 mr-2"><x-select-circle class="border" bg="border-cyan-500"/></span>
            @endif
                <span>
                    <a href="{{route('challenges.show', ['challenge' => $ochallenge])}}"
                        class="font-bold text-gray-800 hover:text-gray-600" wire:navigate>
                        {{ $ochallenge->title }}
                    </a>
                </span>
            </li>
        @endforeach
        </ul>
    </div>
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg w-2/3">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            <a href="{{ route('challenges.edit', $challenge) }}">Edit</a>
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
</div>