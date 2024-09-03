<?php

use App\Providers\RouteServiceProvider;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    use WithFileUploads;

    #[Validate(['files.*' => 'image|max:4096'])]
    public $files = [];

    public function mount()
    {
        if (!auth()->user()->isAdmin)
            $this->redirect(RouteServiceProvider::HOME);
    }

    public function displaySize(int $size): string
    {
        $units = ['B', 'KB', 'MB'];

        for ($i = 0; $size > 1024; $i++) {
            $size /= 1024;
        }

        return round($size) . ' ' . $units[$i];
    }

    public function save()
    {
        $this->validate();

        foreach ($this->files as $file) {
            $file->storeAs(path: 'public', name: $file->getClientOriginalName());
        }

        session()->flash('status', 'files-saved');
    }
}; ?>

<div
    x-data="{
        uploading: false,
        current: 0,
        total: 100,
        handleDrop(e) {
            e.preventDefault();
            const fileInput = document.getElementById('dropzone-file');
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        }
    }"
    x-on:livewire-upload-start="uploading = true"
    x-on:livewire-upload-finish="uploading = false"
    x-on:livewire-upload-cancel="uploading = false"
    x-on:livewire-upload-error="uploading = false"
    x-on:livewire-upload-progress="current = $event.detail.progress"
>
    <x-slot:title>{{ __('Files') }}</x-slot>

    <form wire:submit="save" class="py-6 max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold">{{ __('Files') }}</h1>
        <div class="my-6 flex flex-col">
        @foreach ($files as $file)
            <div class="my-3 flex items-center space-x-3">
                <img src="{{ $file->temporaryUrl() }}" class="max-w-24">
                <div>
                    <p class="font-medium text-gray-800">{{ $file->getClientOriginalName() }}</p>
                    <p class="text-sm text-gray-500">{{ $this->displaySize($file->getSize()) }}</p>
                </div>
            </div>
        @endforeach
        </div>


        <div class="flex items-center justify-center w-full">
            <label for="dropzone-file"
                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                    x-on:dragover.prevent
                    x-on:drop="handleDrop">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Add files here.') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">(MAX. 4MB)</p>
                </div>
                <input wire:model="files" id="dropzone-file" type="file" class="hidden" multiple>
            </label>
        </div>

        <div class="mt-6 flex space-x-3 items-center" x-show="uploading" x-cloak>
            <h1 class="text-xl font-bold">{{ __('Progress') }}</h1>
            <x-progress-bar></x-progress-bar>
        </div>

        <div class="flex space-x-3 items-center">
            <x-primary-button class="mt-6 w-32">
                {{ __('Submit') }}
            </x-primary-button>
        @error('files.*')
            <div class="mt-6 text-red-500 text-sm">{{ $message }}</div>
        @enderror
        @if (session('status') == 'files-saved')
            <div class="mt-6 font-medium text-sm text-green-600">{{ __('Done').'.' }}</div>
        @endif
        </div>
    </form>
</div>
