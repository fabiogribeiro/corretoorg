<?php

use Livewire\Volt\Component;
use App\Models\Challenge;
use App\Models\Question;

new class extends Component
{
    public Challenge $challenge;
    public Question $question;
    public string $answer = '';
    public bool $solved;

    public function mount()
    {
        $this->solved = in_array($this->question->id, auth()->user()->solved['questions']);
        if ($this->solved) $this->answer = $this->question->answer;
    }

    public function submitForm()
    {
        $user = auth()->user();
        if (in_array($this->question->id, $user->solved['questions'])) return;

        if ($this->answer == $this->question->answer) {
            $this->solved = true;
            $user->solved['questions'][] = $this->question->id;

            if ($this->challenge->questions->except($user->solved['questions'])->isEmpty() &&
                !in_array($this->challenge->id, $user->solved['challenges'])) {
                $user->solved['challenges'][] = $this->challenge->id;
            }

            $user->save();
        }
    }

    public function redo()
    {
        $this->solved = false;
        $user = auth()->user();
        $user->solved['questions'] = array_values(array_diff($user->solved['questions'], [$this->question->id]));
        $user->save();
    }
}; ?>

<div>
    <form wire:submit="submitForm" class="py-6">
        <div>
            <div class="flex justify-between">
                <div class="flex space-x-3 items-center w-3/5">
                    <div wire:ignore><x-mmd>{{ $question->statement }}</x-mmd></div>
                </div>
                <div class="flex">
                @if($solved)
                    <div class="flex space-x-3 h-10 self-end">
                        <span class="w-48 self-center" disabled>{{$question->answer}}</span>
                        <x-success-button class="w-20 justify-center"
                                        wire:click="redo"
                                        wire:confirm="{{__('Solve again?')}}">{{ __('Solved') }}</x-success-button>
                    </div>
                @else
                    <div class="flex space-x-3 h-10 self-end">
                        <x-text-input class="w-48" wire:model="answer" id="answer" type="text" autocomplete="answer" />
                        <x-primary-button class="w-20 justify-center">{{ __('Submit') }}</x-primary-button>
                    </div>
                @endif
                </div>
            </div>
            <div class="flex justify-center space-x-3 mt-9">
                <a wire:click.prevent="$dispatch('open-modal', 'notes-modal-{{$question->id}}')" href="#">
                    <div class="flex flex-inline space-x-1">
                        <x-pencil-icon class="self-center"/>
                        <span class="mt-0.5 text-center text-sm font-medium text-gray-600">
                            {{ __('My notes') }}
                        </span>
                    </div>
                </a>
                <livewire:challenges.notes-modal :question="$question"/>
            @if ($question->explanation)
                <a wire:click.prevent="$dispatch('open-modal', 'explanation-modal-{{$question->id}}')" href="#">
                    <div class="flex flex-inline space-x-1">
                        <x-lock-icon class="self-center"/>
                        <span class="mt-0.5 text-sm font-medium text-gray-600">Explanation</span>
                    </div>
                </a>
                <livewire:challenges.explanation-modal :question="$question"/>
            @endif
            </div>
        </div>
    </form>
</div>
