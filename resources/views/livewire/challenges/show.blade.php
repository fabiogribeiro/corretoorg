<?php

use Livewire\Volt\Component;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Challenge;
use App\Models\Question;

new class extends Component
{
    public Challenge $challenge;
    public Collection $other_challenges;

    public function resetProgress()
    {
        $user = auth()->user();
        $ids = $this->challenge->questions->pluck('id')->toArray();

        $user->solved['questions'] = array_diff($user->solved['questions'], $ids);
        $user->solved['challenges'] = array_diff($user->solved['challenges'], [$this->challenge->id]);
        $user->save();

        $this->redirect(route('challenges.show', ['challenge' => $this->challenge]));
    }
} ?>

<div>
    @guest
        <div class="pt-8 text-center">
            <a href="{{ route('login') }}" class="text-cyan-600 font-semibold">{{ __('Log in to solve questions') }}</a>
        </div>
    @endguest
    <div class="p-4 sm:p-8 dark:bg-gray-800">
        <h2 class="text-2xl font-bold mb-12">{{ $challenge->subject }}</h2>
        <ul @class(['mt-6 space-y-2 max-h-56 overflow-y-auto scrollable',
                    'pr-6' => count($other_challenges) > 7])>
        @foreach($other_challenges as $ochallenge)
            @php
                $isCurrentChallenge = $ochallenge->id == $challenge->id;
            @endphp
            <li x-init="{{ $isCurrentChallenge ? '$el.parentElement.scrollTo(0, $el.offsetTop - $el.parentElement.offsetTop - 100)' : '' }}">
                <a href="{{route('challenges.show', ['challenge' => $ochallenge])}}"
                    class="flex justify-between items-center hover:text-blue-700"
                    wire:navigate>
                    <p @class(['text-gray-600' => !$isCurrentChallenge, 'text-blue-600' => $isCurrentChallenge])>{{ $ochallenge->title }}</p>
                @if(in_array($ochallenge->id, auth()->user()->solved['challenges'] ?? []))
                    <x-select-circle bg="bg-emerald-500"/>
                @elseif($isCurrentChallenge)
                    <x-select-circle bg="bg-blue-600"/>
                @else
                    <x-select-circle class="border" bg="border-blue-600"/>
                @endif
                </a>
            </li>
        @endforeach
        </ul>
    </div>
    <div class="mt-6 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow divide-y-2">
        @can('update', $challenge)
        <div class="mb-3">
            <a href="{{ route('challenges.edit', $challenge) }}" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Edit') }}
            </a>
        </div>
        @endcan
        @if($challenge->body)
        <x-mmd class="mb-12" :content="$challenge->body"/>
        @endif
        <div @class(['pt-9' => $challenge->body])>
            <div class="mb-6 flex inline items-center justify-between">
                <h2 class="text-xl font-bold text-gray-700">
                    {{ trans_choice('Questions', 2) }}
                </h2>
                @auth
                <div class="flex inline items-center space-x-3">
                    <a href="#" wire:click.prevent="$dispatch('open-modal', 'help-modal')">
                        <x-question-mark/>
                    </a>
                    <a href="#" wire:click.prevent="resetProgress" wire:confirm="{{ __('Reset progress?') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </a>
                </div>
                @endauth
                <x-modal name="help-modal">
                    <div class="p-4">
                        <x-mmd :content="__('misc.qhelp')"/>
                    </div>
                </x-modal>
            </div>
            <div class="pb-3 border-b-4 border-gray-200">
            @forelse ($challenge->questions->sortBy('statement', SORT_NATURAL) as $question)
                <div @class(['border-b border-gray-200' => !$loop->last])>
                    <livewire:challenges.question :challenge="$challenge" :question="$question" />
                </div>
            @empty
                <div class="py-13">
                    <p class="font-medium text-center text-gray-601">{{ __('No questions to show.') }}</p>
                </div>
            @endforelse
            </div>
        </div>
    </div>
</div>

@script
<script>
    window.getAnswerFromMF = function(id) {
        var mf = document.getElementById(id);

        if (mf.hasAttribute('read-only')) {
            // When we have multiple inputs use mathlive prompts.
            return mf.getPrompts()
                    .map((prompt) => MathLive.convertLatexToAsciiMath(mf.getPromptValue(prompt)), 'latex')
                    .join(';');
        }

        return mf.getValue('ascii-math');
    }

    window.setMFAnswer = function(id, answer) {
        var mf = document.getElementById(id);
        var inner = mf.innerHTML.trim();

        if (inner !== '') {
            // We have a template with placeholders present.
            var parts = answer.split(';').map((s) => MathLive.convertAsciiMathToLatex(s));

            for (let i = 0; i < parts.length; ++i) {
                var regex = new RegExp(String.raw`\\placeholder\[${i+1}\]{}`);
                inner = inner.replace(regex, parts[i]);
            }

            mf.innerText = inner;
        }
        else {
            mf.innerText = MathLive.convertAsciiMathToLatex(answer);
        }
    }

    Livewire.on('set-prompts', (event) => {
        var mf = document.getElementById(event.id);

        for (let i = 0; i < event.state.length; ++i) {
            mf.setPromptState(i + 1 + '', event.state[i] ? 'correct' : 'incorrect');
        }
    });
</script>
@endscript
