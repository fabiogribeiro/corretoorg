<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts.account')] class extends Component
{
    public string $contact = '';

    #[Validate('required|min:10')]
    public string $description = '';

    public function submitReport(): void
    {
        $this->validate();

        $report = [
            'contact' => $this->contact,
            'description' => $this->description,
            'timestamp' => now()->toISOString(),
        ];

        $reportJson = json_encode($report, JSON_PRETTY_PRINT);
        $filename = 'report_' . now()->timestamp . '.json';

        Storage::put('reports/' . $filename, $reportJson);

        $this->reset('contact', 'description');
        session()->flash('status', 'report-submitted');
    }
}; ?>

<div>
    <x-slot:title>{{ __('Report problem') }}</x-slot>

    <form wire:submit="submitReport">
        <h2 class="text-4xl text-gray-800 font-semibold mb-9">{{ __('Report problem') }}</h2>

        <div>
            <x-input-label for="contact" :value="__('Contact information (optional)')" />
            <x-text-input wire:model="contact" id="contact" class="block mt-1 w-full" type="email" name="contact" autofocus autocomplete="email" />
            <x-input-error :messages="$errors->get('contact')" class="mt-2" />
        </div>

        <div class="mt-8">
            <x-input-label for="description" :value="__('Problem description')" />
            <x-multiline-input wire:model="description" id="description" class="block mt-1 w-full" name="description" rows="10" required />
            <x-input-error :messages="$errors->get('description')" class="mt-3" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <x-primary-button>
                {{ __('Submit') }}
            </x-primary-button>

        @if (session('status') === 'report-submitted')
            <p x-transition class="text-sm text-emerald-600">
                {{ __('Report submitted successfully.') }}
            </p>
        @endif
        </div>
    </form>
</div>
