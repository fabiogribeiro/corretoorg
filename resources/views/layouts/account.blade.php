<x-app-layout>
    <x-slot:title>{{ $title ?? 'Title' }}</x-slot:title>

    <div class="bg-gray-100 min-h-screen py-12">
        <div class="w-full sm:w-3/4 md:w-1/2 lg:w-2/5 mx-auto px-12 py-6 bg-white shadow">
            {{ $slot }}
        </div>
    </div>
</x-app-layout>
