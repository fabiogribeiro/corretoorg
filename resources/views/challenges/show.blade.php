<x-app-layout>
    <x-slot:title>{{ $challenge->title }}</x-slot>

    <div class="pb-9 bg-gray-100">
        <div class="max-w-7xl mx-auto">
            <livewire:challenges.show :challenge="$challenge" :other_challenges="$other_challenges" />
            <livewire:challenges.discussion :challenge="$challenge" :comments="$comments"/>
        </div>
    </div>
</x-app-layout>
