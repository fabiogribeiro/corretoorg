<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($challenge->title) }}
        </h2>
    </x-slot>

    <div class="py-12" id="math">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $challenge->title }}
                    </h2>
                    <div class="mt-6">
                        <p class="">
                            {{ $challenge->body }}
                        </p>
                        <p>
                            by: {{ $challenge->author->name  }}
                        </p>
                    </div>
                    <div class="mt-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Questions') }} 
                        </h2>
                    @foreach ($challenge->questions as $question)
                        <livewire:challenges.question :challenge="$challenge" :question="$question" />
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>