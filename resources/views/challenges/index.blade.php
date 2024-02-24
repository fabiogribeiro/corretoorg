<x-app-layout>
    <x-slot:title>{{ __('Challenges') }}</x-slot>

    <div class="py-9 bg-gray-100 min-h-screen">
        <livewire:challenges.index :challenges="$challenges" :questionCount="$questionCount" :solvedCount="$solvedCount"/>
    </div>
</x-app-layout>
