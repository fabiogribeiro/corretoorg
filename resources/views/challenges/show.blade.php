<x-app-layout>
    <x-slot:title>{{ $challenge->title }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $challenge->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <livewire:challenges.show :challenge="$challenge" />
            <livewire:challenges.discussion :challenge="$challenge" :comments="$comments"/>
        </div>
    </div>
</x-app-layout>
