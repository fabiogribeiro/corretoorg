<?php

use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use App\Models\Challenge;

new class extends Component
{
    public Collection $challenges;
    public Collection $filtered_challenges;
    public Collection $questionCount;
    public array $subjects;
    public array $selected_subjects = [];
    public bool $filter_solved = false;
    public bool $filter_unsolved = false;

    public function mount()
    {
        $this->subjects = $this->challenges->pluck('subject')->unique()->toArray();
        $this->filtered_challenges = $this->challenges;
    }

    public function updated($property)
    {
        if ($property === 'filter_solved' ||
            $property === 'filter_unsolved' ||
            str_starts_with($property, 'selected_subjects')) {

            $subject_list = $this->selected_subjects;
            if (!$subject_list) $subject_list = $this->subjects;

            $this->filtered_challenges = $this->challenges->whereIn('subject', $subject_list);

            if ($this->filter_solved !== $this->filter_unsolved) {

                if (auth()->user()) {
                    $this->filtered_challenges = $this->filter_solved ?
                        $this->filtered_challenges->whereIn('id', auth()->user()->solved['challenges']) :
                        $this->filtered_challenges->whereNotIn('id', auth()->user()->solved['challenges']);
                }
                else {
                    $this->filtered_challenges = $this->filter_solved ? new Collection() : $this->filtered_challenges;
                }
            }

            $this->filtered_challenges = $this->filtered_challenges->sortByDesc('title');
        }
    }
}; ?>

<div>
    <div class="sm:hidden px-3 mb-6 mx-auto">
        <div x-data="{ open: false }">
            <a href="#" @click.prevent="open = ! open">
                <div class="flex flex-row justify-between items-center">
                    <h2 class="text-2xl pb-3">
                        {{ __('Filters') }}
                    </h2>
                    <x-select-circle bg="bg-emerald-500" x-show="open" />
                    <x-select-circle bg="border-cyan-500" class="border" x-show="!open" />
                </div>
            </a>
            <div x-show="open">
                <fieldset>
                    <ul class="mt-6">
                        <h3 class="mb-6 font-extralight uppercase text-sm text-gray-500">{{ __('Subject') }}</h3>
                    @foreach($subjects as $subject)
                        <li>
                            <div class="flex items-center mb-1 space-x-3">
                                <input wire:model.change="selected_subjects" id="{{$subject}}-id" type="checkbox" value="{{$subject}}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                <label for="{{$subject}}-id" class="ms-2">{{ $subject }}</label>
                            </div>
                        </li>
                    @endforeach
                    </ul>
                </fieldset>
                <fieldset class="mt-12">
                    <h3 class="mb-6 font-extralight uppercase text-sm text-gray-500">{{ __('Status') }}</h3>
                    <div class="flex items-center mb-1 space-x-3">
                        <input wire:model.change="filter_solved" id="solved-id" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                        <label for="solved-id" class="ms-2">{{ __('Solved') }}</label>
                    </div>
                    <div class="flex items-center mb-1 space-x-3">
                        <input wire:model.change="filter_unsolved" id="unsolved-id" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                        <label for="unsolved-id" class="ms-2">{{ __('Unsolved') }}</label>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="flex inline max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-4 sm:p-0 sm:px-8 dark:bg-gray-800 bg-white w-full sm:w-3/4 shadow divide-y-2">
        @forelse ($filtered_challenges->groupBy('subject') as $subject => $challenge_list)
            <div>
                <h2 class="font-extrabold text-3xl mt-6">{{ $subject }}</h2>
                <ul class="mt-9 divide-y">
                @foreach ($challenge_list as $challenge)
                    <li>
                        <a href="{{route('challenges.show', ['challenge' => $challenge])}}"
                            class="inline py-3 flex justify-between items-center text-gray-800 hover:text-cyan-700">
                            <div class="inline">
                                <p class="font-semibold text-lg">{{ $challenge->title . (auth()->user()?->isAdmin() ? (' - ' . $challenge->stage) : '')}}</p>
                                <p class="mt-1 text-gray-500">{{ ($qc = $questionCount[$challenge->id] ?? 0).' '.trans_choice('Questions', $qc) }}</p>
                            </div>
                        @if(auth()->user() && in_array($challenge->id, auth()->user()->solved['challenges']))
                            <x-select-circle bg="bg-emerald-500" class="ml-2 mr-1"/>
                        @else
                            <x-select-circle bg="border-cyan-500" class="ml-2 mr-1 border"/>
                        @endif
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <div class="flex items-center justify-center h-full">
                <p class="font-medium text-gray-600">{{ __('No challenges to show.') }}</p>
            </div>
        @endforelse
        </div>

        <div class="hidden sm:block pl-6">
            <h2 class="text-2xl w-1/4 mt-6">
                {{ __('Filters') }}
            </h2>
            <fieldset>
                <ul class="mt-9">
                    <h3 class="mb-6 font-extralight uppercase text-sm text-gray-500">{{ __('Subject') }}</h3>
                @foreach($subjects as $subject)
                    <li>
                        <div class="flex items-center mb-2 space-x-3">
                            <input wire:model.change="selected_subjects" id="{{$subject}}-id" type="checkbox" value="{{$subject}}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                            <label for="{{$subject}}-id" class="ms-2">{{ $subject }}</label>
                        </div>
                    </li>
                @endforeach
                </ul>
            </fieldset>
            <fieldset class="mt-12">
                <h3 class="mb-6 font-extralight uppercase text-sm text-gray-500">{{ __('Status') }}</h3>
                <div class="flex items-center mb-2 space-x-3">
                    <input wire:model.change="filter_solved" id="solved-id" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                    <label for="solved-id" class="ms-2">{{ __('Solved') }}</label>
                </div>
                <div class="flex items-center mb-2 space-x-3">
                    <input wire:model.change="filter_unsolved" id="unsolved-id" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                    <label for="unsolved-id" class="ms-2">{{ __('Unsolved') }}</label>
                 </div>
            </fieldset>
        </div>
    </div>
</div>
