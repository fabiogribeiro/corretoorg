<?php

use Livewire\Volt\Component;

use App\Models\Question;

new class extends Component
{
    public Question $question;
}; ?>

<div>
    <form class="py-6">
        <div class="flex flex-col">
            <div class="my-3">
                <div wire:ignore><x-mmd>{{ $question->statement }}</x-mmd></div>
            </div>
            @auth
            <div class="pt-9 flex flex-col sm:flex-row items-center sm:justify-between sm:items-end">
                @switch($question->answer_data['type'])
                    @case('multiple-choice')
                        <livewire:challenges.question-multiple-choice :question="$question"/>
                        @break
                    @case('numeric')
                    @case('expression')
                        <livewire:challenges.question-expression :question="$question"/>
                        @break
                    @case('show')
                    @case('empty')
                        <livewire:challenges.question-show :question="$question"/>
                @endswitch
                <div class="flex space-x-3 mt-24 sm:mt-0">
                    <a wire:click.prevent="$dispatch('open-modal', 'notes-modal-{{$question->id}}')" href="#">
                        <div class="flex flex-inline space-x-1">
                            <x-pencil-icon class="self-center"/>
                            <span class="mt-0.5 text-center text-sm font-medium text-gray-600">
                                {{ __('Notes') }}
                            </span>
                        </div>
                    </a>
                    <livewire:challenges.notes-modal :question="$question"/>
                @if ($question->explanation)
                    <a wire:click.prevent="$dispatch('open-modal', 'explanation-modal-{{$question->id}}')" href="#">
                        <div class="flex flex-inline space-x-1">
                            <x-lock-icon class="self-center"/>
                            <span class="mt-0.5 text-sm font-medium text-gray-600">{{ __('Explanation') }}</span>
                        </div>
                    </a>
                    <livewire:challenges.explanation-modal :question="$question"/>
                @endif
                </div>
            </div>
            @endauth
        </div>
    </form>
</div>
