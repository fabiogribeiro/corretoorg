<x-app-layout>
    <x-slot:title>{{ __('Dashboard') }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="font-extrabold text-3xl text-gray-700 dark:text-gray-300">{{ __('Progress') }}</h2>
                    <ul class="mt-6 space-y-3">
                    @foreach($challenge_data as $subject => $rest)
                        <li>
                            <div class="flex items-center justify-between">
                                <span class="font-bold">{{ $subject }}</span>
                                <span class="inline-block w-1/3">
                                    <x-progress-bar :percentage="$rest['solved_count']/$rest['total_count']*100"/>
                                </span>
                            </div>
                            <p class="text-gray-500">
                                {{ $rest['solved_count'] . ' of ' . $rest['total_count']}}
                            </p>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
