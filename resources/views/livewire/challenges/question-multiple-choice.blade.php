<?php

use Livewire\Volt\Component;

use App\View\QuestionTrait;

new class extends Component
{
    use QuestionTrait;

    public function mount()
    {
        $this->premount();

        if ($this->solved)
            $this->answer = $this->question->answer_data['answer'];
        else
            $this->answer = $this->question->answer_data['options'][0] ?? 'A';
    }

    protected function isCorrectAnswer(): bool
    {
        return $this->answer === $this->question->answer_data['answer'];
    }
}; ?>

<div class="w-full sm:w-3/4 lg:w-1/2">
    <form>
        <div class="flex">
            <x-input-label>{{ __('Answer') }}</x-input-label>
        </div>
        @if ($solved)
        <div class="mt-3 flex flex-col items-center space-y-9 sm:flex-row sm:space-y-0 sm:space-x-3">
            <x-text-input class="w-full text-gray-700" :value="$question->answer_data['answer']" type="text" disabled/>
            <x-success-button class="w-44 sm:w-60"
                            wire:click.prevent="redo"
                            wire:confirm="{{__('Solve again?')}}">
            </x-success-button>
        </div>
        @else
            <div class="mt-3 flex flex-col items-center space-y-9 sm:flex-row sm:space-y-0 sm:space-x-3">
                <div class="w-full">
                    <select wire:model="answer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                <div class="flex">
                    <x-primary-button wire:loading.remove wire:click.prevent="submitForm" class="w-44 h-11">
                        {{ __('Submit') }}
                    </x-primary-button>
                    <div class="w-44 mt-1.5" role="status" wire:loading wire:target="submitForm">
                        <div class="flex justify-center">
                            <x-spinner/>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </form>
</div>
