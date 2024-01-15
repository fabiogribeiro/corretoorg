<x-app-layout>
    <x-slot:title>{{ __('Challenges') }}</x-slot>

    <div class="py-9">
        <livewire:challenges.index :challenges="$challenges"/>
    </div>
</x-app-layout>
