<x-app-layout>
    <x-slot:title>{{ __('Challenges') }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All challenges') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @foreach ($challenges as $subject => $challenge_list)
                <div>
                    <h2 class="mt-6 font-extrabold text-3xl text-gray-700 dark:text-gray-300">{{ $subject }}</h2>
                    <ul class="mt-6 divide-y divide-gray-100">
                    @foreach ($challenge_list as $challenge)
                        <li>
                            <a href="{{route('challenges.show', ['challenge' => $challenge])}}"
                            class="py-3 flex justify-between items-center font-medium inline text-gray-700 hover:text-gray-500">
                                <p class="inline">{{ $challenge->title }}</p>
                            @if(in_array($challenge->id, auth()->user()->solved['challenges']))
                                <x-select-circle bg="bg-emerald-500" class="ml-2 mr-1"/>
                            @else
                                <x-select-circle bg="border-cyan-500" class="ml-2 mr-1 border"/>
                            @endif
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