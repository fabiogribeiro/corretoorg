<?php

use Livewire\Volt\Component;

use App\View\QuestionTrait;

new class extends Component
{
    use QuestionTrait;

    public string $answer;

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

<div>
    <form>
        <div class="flex">
            <x-input-label>{{ __('Answer') }}</x-input-label>
        </div>
        @if ($solved)
        <div class="mt-6 flex flex-col space-y-3">
            <x-text-input class="w-72 text-gray-700" :value="$question->answer_data['answer']" type="text" disabled/>
            <x-success-button class="w-72 justify-center"
                            wire:click.prevent="redo"
                            wire:confirm="{{__('Solve again?')}}">
            </x-success-button>
        </div>
        @else
        <div class="mt-6 space-y-3">
            <div class="flex flex-col w-72">
                <select wire:model="answer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 {{ $submitted? 'ring-1 border-red-400 ring-red-400' : '' }}">
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
                <x-primary-button wire:loading.remove wire:click.prevent="submitForm" class="w-72 justify-center">
                    {{ __('Submit') }}
                </x-primary-button>
                <div class="ml-32" role="status" wire:loading wire:target="submitForm">
                    <x-spinner/>
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        @endif
    </form>
</div>
