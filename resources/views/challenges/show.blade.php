<x-app-layout>
    <x-slot:title>{{ $challenge->title }}</x-slot>

    <div class="py-9">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <livewire:challenges.show :challenge="$challenge" />
            <livewire:challenges.discussion :challenge="$challenge" :comments="$comments"/>
        </div>
    </div>
</x-app-layout>
