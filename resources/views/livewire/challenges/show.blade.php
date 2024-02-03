<?php

use Livewire\Volt\Component;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Challenge;
use App\Models\Question;

new class extends Component
{
    public Challenge $challenge;
    public Collection $other_challenges;
} ?>

<div class="space-y-3">
    @guest
        <div class="pb-6 text-center">
            <a href="{{ route('login') }}" class="text-gray-700">{{ __('Log in to solve questions') }}</a>
        </div>
    @endguest
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <h2 class="text-2xl font-bold text-gray-700">{{ $challenge->subject }}</h2>
        <ul class="mt-6 space-y-1">
        @foreach($other_challenges as $ochallenge)
            <li>
                <a href="{{route('challenges.show', ['challenge' => $ochallenge])}}"
                    class="flex justify-between items-center font-medium text-gray-700 hover:text-gray-500"
                    wire:navigate>
                    <p>{{ $ochallenge->title }}</p>
                @if($ochallenge->id == $challenge->id)
                    <x-select-circle/>
                @elseif(in_array($ochallenge->id, auth()->user()->solved['challenges'] ?? []))
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
        @if($challenge->body)
        <x-mmd class="mb-12">{{ $challenge->body }}</x-mmd>
        @endif
        <div class="mt-3">
            <div class="mb-6 flex inline items-center justify-between">
                <h2 class="text-xl font-bold text-gray-700">
                    {{ __('Questions') }}
                </h2>
                <div class="flex inline items-center space-x-3">
                @can('update', $challenge)
                    <a href="{{ route('challenges.edit', $challenge) }}" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Edit') }}
                    </a>
                @endcan
                    <a href="#" wire:click.prevent="$dispatch('open-modal', 'help-modal')">
                        <x-question-mark/>
                    </a>
                </div>
                <x-modal name="help-modal">
                    <div class="p-4">
                        <x-mmd> {{ __('misc.qhelp') }} </x-mmd>
                    </div>
                </x-modal>
            </div>
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
