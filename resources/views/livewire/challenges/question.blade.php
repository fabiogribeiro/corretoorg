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
    }

    public function submitForm()
    {
        $user = auth()->user();
        if (in_array($this->question->id, $user->solved['questions'])) return;

        if ($this->answer == $this->question->answer) {
            $this->solved = true;
            $user->solved['questions'][] = $this->question->id;

            if ($this->challenge->questions->except($user->solved['questions'])->isEmpty()) {
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
    <form wire:submit="submitForm" class="py-6 space-y-6">
        <div class="flex space-x-3">
            <div wire:ignore><x-mmd>{{ $question->statement }}</x-mmd></div>
            <p {{ $solved ? '' : 'hidden' }} class="text-emerald-500 font-semibold">Solved</p>
        </div>
        <div class="inline-flex space-x-3">
            @if($solved)
                <x-text-input value="{{$question->answer}}" disabled/>
                <x-secondary-button wire:click="redo">{{ __('Redo') }}</x-secondary-button>
            @else
                <x-text-input wire:model="answer" id="answer" type="text" class="flex block" required autocomplete="answer" />
                <x-primary-button>{{ __('Submit') }}</x-primary-button>
            @endif
        </div>
    </form>
</div>
