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

<div">
    @guest
        <div class="pt-8 text-center">
            <a href="{{ route('login') }}" class="text-cyan-600 font-semibold">{{ __('Log in to solve questions') }}</a>
        </div>
    @endguest
    <div class="p-4 sm:p-8 dark:bg-gray-800">
        <h2 class="text-2xl font-bold mb-12">{{ $challenge->subject }}</h2>
        <ul @class(['mt-6 space-y-2 max-h-56 overflow-y-auto scrollable',
                    'pr-6' => count($other_challenges) > 7])>
        @foreach($other_challenges as $ochallenge)
            @php
                $isCurrentChallenge = $ochallenge->id == $challenge->id;
            @endphp
            <li x-init="{{ $isCurrentChallenge ? '$el.parentElement.scrollTo(0, $el.offsetTop - $el.parentElement.offsetTop - 100)' : '' }}">
                <a href="{{route('challenges.show', ['challenge' => $ochallenge])}}"
                    class="flex justify-between items-center hover:text-cyan-700"
                    wire:navigate>
                    <p @class(['text-cyan-700' => $isCurrentChallenge])>{{ $ochallenge->title }}</p>
                @if($isCurrentChallenge)
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
    <div class="mt-6 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow divide-y-2">
        @if($challenge->body)
        <x-mmd class="mb-12">{{ $challenge->body }}</x-mmd>
        @endif
        <div @class(['mt-1' => true, 'pt-12' => $challenge->body])>
            <div class="mb-6 flex inline items-center justify-between">
                <h2 class="text-xl font-bold text-gray-700">
                    {{ trans_choice('Questions', 2) }}
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
            <div class="pb-3 border-b-4 border-gray-200">
            @forelse ($challenge->questions->sortBy('statement', SORT_NATURAL) as $question)
                <div @class(['border-b border-gray-200' => !$loop->last])>
                    <livewire:challenges.question :challenge="$challenge" :question="$question" />
                </div>
            @empty
                <div class="py-13">
                    <p class="font-medium text-center text-gray-601">{{ __('No questions to show.') }}</p>
                </div>
            @endforelse
            </div>
        </div>
    </div>
</div>
