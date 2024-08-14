<div x-data="{ filters: { subjects: [], states: [] } }"
    x-init="$watch('filters', function(value) {
        filtered_challenges = challenges.filter(function(c) {
            return (filters.subjects.length == 0 || filters.subjects.includes(c.subject)) &&
                    (filters.states.length == 0 || filters.states.includes(c.state));
        });

        grouped_challenges = Object.groupBy(filtered_challenges, ({ subject }) => subject);
    })"
    class="pl-6"
>
    <fieldset>
        <ul>
            <h3 class="mb-6 font-bold uppercase text-sm text-gray-800">{{ __('Subject') }}</h3>
            <template x-for="subject in subjects">
                <li>
                    <div x-id="[subject]" class="flex items-center mb-2 space-x-3">
                        <input x-model="filters.subjects" :id="$id(subject)" type="checkbox" :value="subject" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
                        <label x-text="subject" :for="$id(subject)" class="ms-2 text-gray-600"></label>
                    </div>
                </li>
            </template>
        </ul>
    </fieldset>
    <fieldset class="mt-12">
        <h3 class="mb-6 font-bold uppercase text-sm text-gray-800">{{ __('Status') }}</h3>
        <div x-id=['progress'] class="flex items-center mb-2 space-x-3">
            <input x-model="filters.states" :id="$id('progress')" type="checkbox" value="progress" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
            <label :for="$id('progress')" class="ms-2 text-gray-600">{{ __('In progress') }}</label>
        </div>
        <div x-id=['unsolved'] class="flex items-center mb-2 space-x-3">
            <input x-model="filters.states" :id="$id('unsolved')" type="checkbox" value="unsolved" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
            <label :for="$id('unsolved')" class="ms-2 text-gray-600">{{ __('Unsolved') }}</label>
        </div>
        <div x-id=['solved'] class="flex items-center mb-2 space-x-3">
            <input x-model="filters.states" :id="$id('solved')" type="checkbox" value="solved" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
            <label :for="$id('solved')" class="ms-2 text-gray-600">{{ __('Solved') }}</label>
        </div>
    </fieldset>
</div>
