<x-app-layout>
    <x-slot:title>{{ __('Edit') . ' ' . $challenge->title }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editing') }} "<a href="{{route('challenges.show', ['challenge' => $challenge])}}"
                    class="text-gray-600">{{ __($challenge->title) }}</a>"
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow">
                <div class="w-full">
                    <livewire:challenges.edit :challenge='$challenge'/>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>