<?php

use Livewire\Volt\Component;

use App\View\QuestionTrait;

new class extends Component
{
    use QuestionTrait;

    public bool $isCheckBox = false;

    public function mount()
    {
        $this->premount();
        $this->answer = [];

        if ($this->solved)
            $this->answer = $this->question->answer;
    }

    protected function isCorrectAnswer(): bool
    {
        if ($this->isCheckBox)
            return !array_diff(explode(';', $this->question->answer), $this->answer);

        return $this->answer === $this->question->answer;
    }
}; ?>

<div class="w-full sm:w-3/4 lg:w-1/2">
    <form>
        <div class="flex">
            <x-input-label>{{ __('Answer') }}</x-input-label>
        </div>
        <div class="mt-3 min-h-11 flex flex-col items-center space-y-9 sm:flex-row sm:space-y-0 sm:space-x-6">
            <div class="w-full">
            @if ($isCheckBox)
            @else
                <div class="flex justify-between">
                    @foreach ($question->options as $i => $o)
                        @php $isCorrect = $o === $question->answer; @endphp

                        <div class="flex">
                            <label for="hs-radio-group-{{$i}}" class="text-sm text-gray-500 ms-2 dark:text-neutral-400">
                                <input @disabled($solved) wire:model="answer" type="radio" value="{{$o}}" name="hs-radio-group" @class(["shrink-0 mb-0.5 mr-1.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-90 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800", "text-emerald-600" => $solved && $isCorrect]) id="hs-radio-group-{{$i}}"/>
                                {{ $o }}
                            </label>
                        </div>
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
