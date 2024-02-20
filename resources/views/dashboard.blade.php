<x-app-layout>
    <x-slot:title>{{ __('Dashboard') }}</x-slot>

    @if($show_verified)
    <div x-data="{ vshow: true }" x-show="vshow" tabindex="-1" class="fixed top-0 start-0 z-50 flex justify-between w-full p-6 border-b border-gray-200 bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
        <div class="flex items-center mx-auto">
            <p class="flex items-center text-sm font-semibold text-green-500">
                <span>{{ __('E-mail successfully verified.') }}</span>
            </p>
        </div>
        <div class="flex items-center">
            <button @click="vshow = false" type="button" class="flex-shrink-0 inline-flex justify-center w-7 h-7 items-center text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 dark:hover:bg-gray-600 dark:hover:text-white">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close banner</span>
            </button>
        </div>
    </div>
    @endif

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="font-extrabold text-3xl">{{ __('Progress') }}</h2>
                    <ul class="mt-16 space-y-3">
                    @forelse($challenge_data as $subject => $rest)
                        <li>
                            <div class="flex items-center justify-between">
                                <span class="font-bold">{{ $subject }}</span>
                                <span class="inline-block w-1/3">
                                    <x-progress-bar :percentage="$rest['solved_count']/$rest['total_count']*100"/>
                                </span>
                            </div>
                            <p class="text-gray-500">
                                {{ $rest['solved_count'] . ' ' . __('of') . ' '. $rest['total_count']}}
                            </p>
                        </li>
                    @empty
                        <div class="py-12">
                            <p class="my-12 font-medium text-center text-gray-600">{{ __('No challenges solved yet.') }}</p>
                        </div>
                    @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
