<?php

use Livewire\Volt\Component;
use App\Models\Challenge;
use App\Models\Question;

new class extends Component
{
    public Challenge $challenge;
    public Question $question;
    public string $answer = '';

    public function submitForm()
    {
        $user = auth()->user();
        if (in_array($this->question->id, $user->solved['questions'])) return;

        if ($this->answer == $this->question->answer) {
            $user->solved['questions'][] = $this->question->id;

            if ($this->challenge->questions->except($user->solved['questions'])->isEmpty()) {
                $user->solved['challenges'][] = $this->challenge->id;
            }

            $user->save();
        }
    }
}; ?>

<div>
    <form wire:submit="submitForm" class="py-6 space-y-6">
        <div wire:ignore><x-mmd>{{ $question->statement }}</x-mmd></div>
        <x-text-input wire:model="answer" id="answer" type="text" class="mt-1 block w-1/2" required autocomplete="answer" />
        <x-primary-button>{{ __('Submit') }}</x-primary-button>
    </form>
</div>
