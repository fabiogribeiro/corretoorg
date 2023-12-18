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
    public bool $submitted = false;

    public function mount()
    {
        $this->solved = in_array($this->question->id, auth()->user()->solved['questions']);
        if ($this->solved) $this->answer = $this->question->answer;
    }

    public function submitForm()
    {
        $user = auth()->user();
        if (in_array($this->question->id, $user->solved['questions'])) return;

        if (($this->question->type !== 'multiple-choice') ||
            ($this->question->type === 'multiple-choice' && $this->answer == $this->question->answer)) {

            $this->solved = true;
            $user->solved['questions'][] = $this->question->id;

            if ($this->challenge->questions->except($user->solved['questions'])->isEmpty() &&
                !in_array($this->challenge->id, $user->solved['challenges'])) {
                $user->solved['challenges'][] = $this->challenge->id;
            }

            $user->save();
        }
        else {
            $this->submitted = true;
        }
    }
    public function unsubmit()
    {
        $this->submitted = false;
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
    <form class="py-6">
        <div>
            <div class="flex justify-between">
                <div class="flex space-x-3 items-center w-3/5">
                    <div wire:ignore><x-mmd>{{ $question->statement }}</x-mmd></div>
                </div>
                <div class="flex flex-col space-y-3 self-end">
                @if($solved)
                    @if($question->type !== 'empty')
                        <div>
                            <x-input-label>{{ __('Answer') }}</x-input-label>
                        </div>
                    @endif
                    <div class="flex flex-row space-x-3 h-10">
                    @if($question->type !== 'empty')
                        <x-text-input class="w-52 disabled:border-emerald-400 text-gray-700" :value="$question->answer" type="text" disabled/>
                    @endif
                        <x-success-button class="w-26 justify-center"
                                        wire:click="redo"
                                        wire:confirm="{{__('Solve again?')}}">{{ __('Done') }}</x-success-button>
                    </div>
                @else
                    @if($question->type === 'multiple-choice')
                        <div>
                            <x-input-label>{{ __('Answer') }}</x-input-label>
                        </div>
                    @endif
                    <div class="flex space-x-3 h-10">
                    @if ($question->type === 'multiple-choice')
                        <div class="flex space-x-3 w-72">
                            <select wire:model="answer" wire:click="unsubmit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 {{ $submitted? 'border-red-500':'' }}">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                            <x-primary-button wire:click.prevent="submitForm" class="w-26 justify-center">{{ __('Done') }}</x-primary-button>
                        </div>
                    @else
                        <x-primary-button wire:click.prevent="submitForm"
                                        wire:confirm="{{$question->type === 'show' ? __('Show answer?') : __('Mark as solved?')}}"
                                        class="w-26 justify-center">{{ __('Done') }}</x-primary-button>
                    @endif
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
