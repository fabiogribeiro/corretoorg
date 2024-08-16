<?php

use Livewire\Volt\Component;

use App\View\QuestionTrait;

new class extends Component
{
    use QuestionTrait;

    public bool $isCheckBox = false;
    public array $checkAnswer = [];
    public array $answerParts = [];

    public function mount()
    {
        $this->premount();

        $this->answerParts = explode(";", $this->question->answer);
        if (count($this->answerParts) > 1)
            $this->isCheckBox = true;

        if ($this->solved) {
            if ($this->isCheckBox) {
                $this->checkAnswer = $this->answerParts;
            }
            else {
                $this->answer = $this->question->answer;
            }
        }
    }

    protected function isCorrectAnswer(): bool
    {
        if ($this->isCheckBox) {
            return count($this->answerParts) === count($this->checkAnswer) &&
                    !array_diff($this->answerParts, $this->checkAnswer);
        }

        return $this->answer === $this->question->answer;
    }
}; ?>

<div class="w-full sm:w-3/4 lg:w-1/2">
    <form>
        <div class="flex">
            <x-input-label>{{ __('Answer') }}</x-input-label>
        </div>
        <div class="mt-3 min-h-11 flex flex-col items-center sm:items-end space-y-9 sm:flex-row sm:space-y-0 sm:space-x-6">
            <div class="w-full">
            @if ($isCheckBox)
                <div class="grid space-y-2">
                @foreach ($question->options ?: ["A", "B", "C", "D"] as $i => $option)
                    @php $isCorrect = in_array($option, $answerParts); @endphp

                    <label for="q{{$question->id}}-checkbox{{$i}}" class="sm:max-w-xs sm:mx-0 flex p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                        <input @disabled($solved) wire:model="checkAnswer" value="{{$option}}" type="checkbox" @class(["shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-80 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800", "text-emerald-600" => $solved && $isCorrect]) id="q{{$question->id}}-checkbox{{$i}}">
                        <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">{{ $option }}</span>
                    </label>
                @endforeach
                </div>
            @else
                <div class="grid space-y-2">
                @foreach ($question->options ?: ["A", "B", "C", "D"] as $i => $option)
                    @php $isCorrect = $option === $question->answer; @endphp

                    <label for="q{{$question->id}}-radio{{$i}}" class="sm:max-w-xs sm:mx-0 flex p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                        <input @disabled($solved) wire:model="answer" value="{{$option}}" type="radio" @class(["shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-80 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800", "text-emerald-600" => $solved && $isCorrect]) id="q{{$question->id}}-radio{{$i}}">
                        <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">{{ $option }}</span>
                    </label>
                @endforeach
                </div>
            @endif
            </div>
            <div class="flex min-h-11">
            @if ($solved)
                <x-success-button wire:click.prevent="redo" wire:confirm="{{__('Solve again?')}}" class="w-44 h-11"></x-success-button>
            @else
                <div>
                    <x-primary-button wire:loading.remove wire:click.prevent="submitForm" class="w-44 h-11">
                        {{ __('Submit') }}
                    </x-primary-button>
                </div>
            @endif
                <div class="w-44 mt-1.5" role="status" wire:loading wire:target="submitForm">
                    <div class="flex justify-center">
                        <x-spinner/>
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
