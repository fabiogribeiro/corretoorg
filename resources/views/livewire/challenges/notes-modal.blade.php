<?php

use Livewire\Volt\Component;
use App\Models\Question;

new class extends Component
{
    public Question $question;
    public string $note;

    public function mount()
    {
        $user = auth()->user();
        $this->note = isset($user->notes[$this->question->id]) ? $user->notes[$this->question->id ] : '';
    }

    public function save()
    {
        $user = auth()->user();
        $user->notes[$this->question->id] = $this->note;
        $user->save();
    }
}; ?>

<div>
    <x-modal name="notes-modal-{{ $question->id }}" :show="false">
        <div class="p-4 space-y-6">
            <x-multiline-input wire:model="note" type="text" class="mt-1 block w-full" rows="18"/>
            <x-primary-button wire:click="save">{{ __('Save') }}</x-primary-button>
        </div>
    </x-modal>
</div>
