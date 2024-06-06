<?php

use Livewire\Volt\Component;
use App\Models\Question;

new class extends Component
{
    public Question $question;
}; ?>

<div>
    <x-modal name="explanation-modal-{{ $question->id }}" :show="false" maxWidth="6xl">
        <div class="p-4 space-y-6 overflow-x-auto">
            <h1 class="font-semibold text-lg">{{ __('Explanation') }}</h1>
            <x-mmd>{{ $question->explanation }}</x-mmd>
        </div>
    </x-modal>
</div>
