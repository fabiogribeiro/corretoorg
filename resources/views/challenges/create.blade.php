<x-app-layout>
    <x-slot:title>{{ __('Create challenge') }}</x-slot>
    <div class="py-9">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:challenges.create />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
