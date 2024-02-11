<x-app-layout>
    <x-slot:title>{{ $title ?? 'Title' }}</x-slot:title>

    <div class="w-full sm:w-3/4 md:w-1/2 lg:w-1/4 mx-auto py-12">
        {{ $slot }}
    </div>
</x-app-layout>
