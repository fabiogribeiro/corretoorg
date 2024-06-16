<?php

use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use App\Models\Challenge;

new class extends Component
{
    public array $challenges;
    public array $subjects;
}; ?>

<div x-data="{ challenges: $wire.challenges,
                subjects: Object.values($wire.subjects),
                filtered_challenges: $wire.challenges }">
    <div class="sm:hidden mb-9">
        <x-challenge-filter/>
    </div>
    <div class="flex inline max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-4 sm:py-3 sm:px-8 dark:bg-gray-800 bg-white w-full sm:w-3/4 shadow">
            <template x-for="[subject, chals] in Object.entries(Object.groupBy(filtered_challenges, ({ subject }) => subject))" hidden>
                <div class="py-3">
                    <h2 x-text="subject" class="font-extrabold text-3xl"></h2>
                    <ul class='mt-9 divide-y max-h-[800px] overflow-y-auto scrollable mr-[-1rem] pr-2 sm:mr-[-2rem] sm:pr-5'>
                        <template x-for="chal in chals" hidden>
                            <li>
                                <a :href="chal.url"
                                    class="py-3 flex justify-between items-center text-gray-800 hover:text-cyan-700">
                                    <div class="w-full">
                                        <p x-text="chal.title" class="font-semibold text-lg"></p>
                                        <div class="flex items-center space-x-6 mt-3">
                                            <div class="w-1/2">
                                                <x-progress-bar x-data="{ current: chal.solvedCount, total: chal.questionCount }"/>
                                            </div>
                                            <p x-text="chal.solvedCount + ' / ' + chal.questionCount"
                                                class="text-gray-500">
                                            </p>
                                        </div>
                                    </div>
                                    <div x-show="chal.state == 'solved'">
                                        <x-select-circle bg="bg-emerald-500" class="ml-2 mr-1"/>
                                    </div>
                                    <div x-show="chal.state == 'progress'">
                                        <x-select-circle bg="bg-blue-600" class="ml-2 mr-1"/>
                                    </div>
                                    <div x-show="chal.state == 'unsolved'">
                                        <x-select-circle bg="border-blue-600" class="ml-2 mr-1 border"/>
                                    </div>
                                </a>
                            </li>
                        </template>
                    </ul>
                </div>
            </template>
            <div x-show="filtered_challenges.length === 0" class="flex items-center justify-center h-full">
                <p class="font-medium text-gray-600">{{ __('No challenges to show.') }}</p>
            </div>
        </div>
        <div class="hidden sm:block mt-3">
            <x-challenge-filter/>
        </div>
    </div>
</div>
