<?php

use Livewire\Volt\Component;
use App\Models\Challenge;
use Illuminate\Support\Str;

new class extends Component
{
    public string $title = '';
    public string $slug = '';
    public string $subject = '';

    public function submitForm()
    {
        $this->slug = Str::slug($this->title, '-');
        $validated = $this->validate([
            'title' => 'required|min:3',
            'slug' => 'required|min:3',
            'subject' => 'required|min:3']);
        $challenge = Challenge::create($validated);

        return redirect()->route('challenges.edit', ['challenge' => $challenge]);
    }
}; ?>

<section class="flex">
    <form wire:submit="submitForm" class="space-y-6 w-full">
        <x-input-label for="title" :value="__('Title')" />
        <div class="flex gap-6 w-full">
            <x-text-input wire:model="title" id="title" title="title" type="text" class="block w-full" required autofocus autocomplete="title" />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>
        <x-input-label for="subject" :value="__('Subject')" />
        <div class="flex gap-6 w-full">
            <x-text-input wire:model="subject" id="subject" subject="subject" type="text" class="block w-full" required autofocus autocomplete="subject" />
            <x-input-error class="mt-2" :messages="$errors->get('subject')" />
        </div>
        <x-primary-button>{{ __('Submit') }}</x-primary-button>
    </form>
</section>