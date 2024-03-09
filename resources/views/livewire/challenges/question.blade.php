<?php

use Livewire\Volt\Component;

use SymPHP\Parser\Parser;

use App\Models\Challenge;
use App\Models\Question;

new class extends Component
{
    public Challenge $challenge;
    public Question $question;
    public string $answer = '';
    public bool $solved;
    public bool $submitted = false;

    public function mount()
    {
        $this->solved = auth()->user() && in_array($this->question->id, auth()->user()->solved['questions']);

        if ($this->solved) {
            $this->answer = $this->question->answer_data['answer'];
        }
        elseif ($this->question->answer_data['type'] === 'multiple-choice') {
            $this->answer = $this->question->answer_data['options'][0] ?? 'A';
        }
    }

    public function submitForm()
    {
        $user = auth()->user();
        if (in_array($this->question->id, $user->solved['questions'])) return;

        if ($this->isCorrectAnswer()) {
            $this->solved = true;
            $user->solved['questions'][] = $this->question->id;

            if ($this->challenge->questions->except($user->solved['questions'])->isEmpty() &&
                !in_array($this->challenge->id, $user->solved['challenges'])) {
                $user->solved['challenges'][] = $this->challenge->id;
            }

            $user->save();
        }
        else {
            $this->submitted = true;
        }
    }

    public function unsubmit()
    {
        $this->submitted = false;
    }

    public function redo()
    {
        $this->solved = false;
        $user = auth()->user();
        $user->solved['questions'] = array_values(array_diff($user->solved['questions'], [$this->question->id]));
        $user->save();
    }

    private function isCorrectAnswer(): bool
    {
        $type = $this->question->answer_data['type'];
        if ($type === 'empty' || $type === 'show') {
            return true;
        }

        $answers = explode(';', $this->question->answer_data['answer']);
        $inputs = explode(';', $this->answer);

        $n = count($answers);
        if (count($inputs) !== $n) return false;

        if ($type === 'multiple-choice') {
            for ($i = 0; $i < $n; $i++) {
                if ($answers[$i] !== $inputs[$i])
                    return false;
            }

            return true;
        }
        else { // Numeric or expression type
            $parser = new Parser();

            for ($i = 0; $i < $n; $i++) {
                try {
                    $answer = $parser->parse($answers[$i])->flatten()->simplify();
                    $input = $parser->parse($inputs[$i])->flatten()->simplify();

                    if (!($answer->equals($input, 1e-7)))
                        return false;
                }
                catch (Exception $e) {
                    return false;
                }
            }

          return true;
        }

        return false;
    }
}; ?>

<div class="pb-12">
    <form class="py-6">
        <div class="flex flex-col">
            <div class="my-3">
                <div wire:ignore><x-mmd>{{ $question->statement }}</x-mmd></div>
            </div>
            @auth
            <div class="flex flex-col sm:flex-row items-center sm:justify-between sm:items-end">
                <div class="my-6 space-y-3">
                @if($solved)
                    @if($question->answer_data['type'] !== 'empty')
                        <div>
                            <x-input-label>{{ __('Answer') }}</x-input-label>
                        </div>
                    @endif
                    <div class="flex flex-col space-y-6 h-10">
                    @if($question->answer_data['type'] === 'multiple-choice')
                        <x-text-input class="w-72 text-gray-700" :value="$question->answer_data['answer']" type="text" disabled/>
                    @elseif ($question->answer_data['type'] === 'show')
                        <div class="inline" x-init="$nextTick(() => MathJax.typeset([$el]))">
                            $ {{ str_contains($answer = $question->answer_data['answer'], ';') ? '\left( ' . $answer . ' \right)' : $answer }} $
                        </div>
                    @elseif ($question->answer_data['type'] !== 'empty')
                        <div class="inline" x-init="$nextTick(() => MathJax.typeset([$el]))">
                            ` {{ str_contains($answer = $question->answer_data['answer'], ';') ? '(' . $answer . ')' : $answer }} `
                        </div>
                    @endif
                        <x-success-button class="w-72 justify-center"
                                        wire:click.prevent="redo"
                                        wire:confirm="{{__('Solve again?')}}">{{ __('Done') }}</x-success-button>
                    </div>
                @else
                    @if($question->answer_data['type'] === 'multiple-choice'    ||
                        $question->answer_data['type'] === 'expression'         ||
                        $question->answer_data['type'] === 'numeric')

                        <div>
                            <x-input-label>{{ __('Answer') }}</x-input-label>
                        </div>
                    @endif

                    <div class="flex flex-col space-y-6 h-10">
                    @if ($question->answer_data['type'] === 'multiple-choice')
                        <div class="flex flex-col space-y-3 w-72">
                            <select wire:model="answer" wire:click="unsubmit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 {{ $submitted? 'ring-1 border-red-400 ring-red-400' : '' }}">
                            @forelse ($question->answer_data['options'] as $op)
                                <option value="{{ $op }}">{{ $op }}</option>
                            @empty
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            @endforelse
                            </select>
                        </div>
                    @elseif ($question->answer_data['type'] === 'expression' ||
                            $question->answer_data['type'] === 'numeric')

                        <div class="flex flex-col space-y-6 space-x-3 sm:flex-row sm:space-y-0 sm:items-center">
                            <x-text-input wire:click="unsubmit" wire:model="answer" class="w-72 text-gray-700 {{ $submitted ? 'ring-1 border-red-400 ring-red-400' : '' }}" type="text" placeholder="{{ __('Insert expression') }}"/>
                            <div x-init="$watch('$wire.answer', value => {
                                $el.textContent = '` ' + value + ' `';
                                MathJax.typeset([$el]);
                            })">
                            </div>
                        </div>
                    @endif
                    <div class="flex mt-9">
                        <x-primary-button wire:loading.remove wire:click.prevent="submitForm" class="w-72 justify-center">
                        @if ($question->answer_data['type'] === 'show' ||
                            $question->answer_data['type'] === 'empty')

                            {{ __('Continue') }}
                        @else
                            {{ __('Submit') }}
                        @endif
                        </x-primary-button>
                        <div class="ml-32" role="status" wire:loading wire:target="submitForm">
                            <x-spinner/>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                @endif
                </div>
                <div class="flex space-x-3 mt-24 sm:mt-0">
                    <a wire:click.prevent="$dispatch('open-modal', 'notes-modal-{{$question->id}}')" href="#">
                        <div class="flex flex-inline space-x-1">
                            <x-pencil-icon class="self-center"/>
                            <span class="mt-0.5 text-center text-sm font-medium text-gray-600">
                                {{ __('My notes') }}
                            </span>
                        </div>
                    </a>
                    <livewire:challenges.notes-modal :question="$question"/>
                @if ($question->explanation)
                    <a wire:click.prevent="$dispatch('open-modal', 'explanation-modal-{{$question->id}}')" href="#">
                        <div class="flex flex-inline space-x-1">
                            <x-lock-icon class="self-center"/>
                            <span class="mt-0.5 text-sm font-medium text-gray-600">{{ __('Explanation') }}</span>
                        </div>
                    </a>
                    <livewire:challenges.explanation-modal :question="$question"/>
                @endif
                </div>
            </div>
            @endauth
        </div>
    </form>
</div>
