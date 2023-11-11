<x-app-layout>
    <x-slot:title>{{ __('Challenges') }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All challenges') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <livewire:challenges.index :challenges="$challenges"/>
    </div>
</x-app-layout>
