<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All challenges') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @foreach ($challenges as $subject => $challenge_list)
                <div class="max-w-xl">
                    <h2 class="font-medium text-xl text-gray-700 dark:text-gray-300">{{ $subject }}</h2>
                    <ul class="my-6">
                    @foreach ($challenge_list as $challenge)
                        <li class="space-x-2">
                        @if(in_array($challenge->id, auth()->user()->solved['challenges']))
                            <x-check-circle class="inline fill-emerald-500 mb-1"/>
                        @else
                            <x-minus-icon class="inline fill-blue-500"/>
                        @endif
                            <a href="{{route('challenges.show', ['challenge' => $challenge])}}"
                            class="inline text-gray-500 hover:text-gray-700">
                                <p class="inline text-lg">{{ $challenge->title }}</p>
                            </a>
                        </li>
                    @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>