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
    }
}; ?>

<div>
    <form wire:submit="submitForm" class="py-6 space-y-6">
        <p>
            {{ $question->statement }}
        </p>
        <x-text-input wire:model="answer" id="answer" type="text" class="mt-1 block w-1/2" required autocomplete="answer" />
        <x-primary-button>{{ __('Submit') }}</x-primary-button>
    </form>
</div>
