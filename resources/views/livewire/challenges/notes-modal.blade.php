<?php

use Livewire\Volt\Component;
use App\Models\Question;

new class extends Component
{
    public Question $question;
    public bool $editing = false;
    public string $note;

    public function mount()
    {
        $user = auth()->user();
        $this->note = isset($user->notes[$this->question->id]) ? $user->notes[$this->question->id] : '';
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function save()
    {
        $user = auth()->user();
        $user->notes[$this->question->id] = $this->note;
        $user->save();

        $this->editing = false;
    }
}; ?>

<div>
    <x-modal name="notes-modal-{{ $question->id }}" :show="false">
    @if ($editing)
        <div class="p-4 space-y-6">
            <x-multiline-input wire:model="note" type="text" class="mt-1 block w-full" rows="18"/>
            <x-primary-button wire:click="save">{{ __('Save') }}</x-primary-button>
        </div>
    @else
        <div class="p-4 space-y-6">
        @if($note)
            <x-mmd>{{ $note }}</x-mmd>
        @else
            <p class="font-medium text-gray-500">{{ __('Add notes here.') }}</p>
        @endif
            <x-primary-button wire:click="edit">{{ __('Edit') }}</x-primary-button>
        </div>
    @endif
    </x-modal>
</div>
