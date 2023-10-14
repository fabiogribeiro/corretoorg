<?php

use Livewire\Volt\Component;
use App\Models\Challenge;
use Illuminate\Support\Str;

new class extends Component
{
    public Challenge $challenge;
    public bool $editing = false;
    public string $title = '';
    public string $body = '';

    public function mount()
    {
        $this->title = $this->challenge->title;
        $this->body = $this->challenge->body;
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function save()
    {
        $this->editing = false;
        $this->challenge->update([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'body' => $this->body
        ]);
    }
}; ?>

<div>
    @unless ($editing)
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ $challenge->title }}
        </h2>
        <div class="my-6">
            {{ $challenge->body }}
        </div>
    @else
        <form wire:submit="submitForm" class="space-y-6 mb-6">
            <div class="w-1/2">
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input wire:model="title" value="{{ $challenge->title }}" id="title" title="title" type="text" class="mt-1 block w-full" required autofocus autocomplete="title" />
                <x-input-error class="mt-2" :messages="$errors->get('title')" />
            </div>
            <div>
                <x-input-label for="body" :value="__('Body')" />
                <x-text-input wire:model="body" value="{{ $challenge->body }}" id="body" body="body" type="text" class="mt-1 block w-full" required autofocus autocomplete="body" />
                <x-input-error class="mt-2" :messages="$errors->get('body')" />
            </div>
        </form>
    @endunless 

    
    @unless ($editing)
        <x-primary-button wire:click="edit">
            Edit
        </x-primary-button>
    @else
        <x-primary-button wire:click="save">
            Save
        </x-primary-button>
    @endunless

    <x-primary-button>
        New question
    </x-primary-button>
</div>