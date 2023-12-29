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

<div class="space-y-3">
    <div class="pb-6 text-center">
        <a href="{{ route('login') }}" class="text-gray-700">{{ __('Log in to solve questions') }}</a>
    </div>
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <h2 class="text-2xl font-bold text-gray-700">{{ $challenge->subject }}</h2>
        <ul class="mt-6">
        @foreach($other_challenges as $ochallenge)
            <li>
                <a href="{{route('challenges.show', ['challenge' => $ochallenge])}}"
                    class="flex justify-between items-center font-medium text-gray-700 hover:text-gray-500"
                    wire:navigate>
                    <p>{{ $ochallenge->title }}</p>
                @if($ochallenge->id == $challenge->id)
                    <x-select-circle/>
                @elseif(in_array($ochallenge->id, auth()->user()->solved['challenges']))
                    <x-select-circle bg="bg-emerald-500"/>
                @else
                    <x-select-circle class="border" bg="border-cyan-500"/>
                @endif
                </a>
            </li>
        @endforeach
        </ul>
    </div>
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    @can('update', $challenge)
        <h2 class="mb-3 text-lg font-medium text-gray-900 dark:text-gray-100">
            <a href="{{ route('challenges.edit', $challenge) }}">Edit</a>
        </h2>
    @endcan
        <x-mmd class="mb-12">{{ $challenge->body }}</x-mmd>
        <div class="mt-3">
            <h2 class="mb-6 text-xl font-bold text-gray-700">
                {{ __('Questions') }}
            </h2>
        @forelse ($challenge->questions->sortBy('statement', SORT_NATURAL) as $question)
            <livewire:challenges.question :challenge="$challenge" :question="$question" />
        @empty
            <div class="py-12">
                <p class="font-medium text-center text-gray-600">{{ __('No questions to show.') }}</p>
            </div>
        @endforelse
        </div>
    </div>
</div>
