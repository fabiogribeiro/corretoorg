<?php

use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use App\Models\Challenge;

new class extends Component
{
    public Collection $challenges;
    public Collection $filtered_challenges;
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
        if ($property === 'filter_solved' || $property === 'filter_unsolved' || str_starts_with($property, 'selected_subjects')) {
            $subject_list = $this->selected_subjects;
            if (!$subject_list) $subject_list = $this->subjects;

            $this->filtered_challenges = $this->challenges->whereIn('subject', $subject_list);

            if ($this->filter_solved === $this->filter_unsolved) return;

            if ($this->filter_solved)
                $this->filtered_challenges = $this->filtered_challenges->whereIn('id', auth()->user()->solved['challenges']);
            elseif ($this->filter_unsolved)
                $this->filtered_challenges = $this->filtered_challenges->whereNotIn('id', auth()->user()->solved['challenges']);
        }
    }
}; ?>

<div>
    <div class="flex inline max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 space-x-9">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg w-3/4">
        @foreach ($filtered_challenges->groupBy('subject') as $subject => $challenge_list)
            <div>
                <h2 class="mt-6 font-extrabold text-3xl text-gray-700 dark:text-gray-300">{{ $subject }}</h2>
                <ul class="mt-6 divide-y divide-gray-100">
                @foreach ($challenge_list as $challenge)
                    <li>
                        <a href="{{route('challenges.show', ['challenge' => $challenge])}}"
                        class="py-3 flex justify-between items-center font-medium inline text-gray-700 hover:text-gray-500">
                            <p class="inline">{{ $challenge->title }}</p>
                        @if(in_array($challenge->id, auth()->user()->solved['challenges']))
                            <x-select-circle bg="bg-emerald-500" class="ml-2 mr-1"/>
                        @else
                            <x-select-circle bg="border-cyan-500" class="ml-2 mr-1 border"/>
                        @endif
                        </a>
                    </li>
                @endforeach
                </ul>
            </div>
        @endforeach
        </div>

        <div class="px-3">
            <h2 class="font-extrabold text-2xl text-gray-700 w-1/4">
                {{ __('Filters') }}
            </h2>
            <fieldset>
                <ul class="mt-6">
                @foreach($subjects as $subject)
                    <li>
                        <div class="flex items-center mb-4 space-x-3">
                            <input wire:model.change="selected_subjects" id="{{$subject}}-id" type="checkbox" value="{{$subject}}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                            <label for="{{$subject}}-id" class="ms-2 font-medium text-gray-600 dark:text-gray-400">{{ $subject }}</label>
                        </div>
                    </li>
                @endforeach
                </ul>
            </fieldset>
            <fieldset class="mt-12">
                <div class="flex items-center mb-4 space-x-3">
                    <input wire:model.change="filter_solved" id="solved-id" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                    <label for="solved-id" class="ms-2 font-medium text-gray-600 dark:text-gray-400">{{ __('Solved') }}</label>
                </div>
                <div class="flex items-center mb-4 space-x-3">
                    <input wire:model.change="filter_unsolved" id="unsolved-id" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                    <label for="unsolved-id" class="ms-2 font-medium text-gray-600 dark:text-gray-400">{{ __('Unsolved') }}</label>
                 </div>
            </fieldset>
        </div>
    </div>
</div>
