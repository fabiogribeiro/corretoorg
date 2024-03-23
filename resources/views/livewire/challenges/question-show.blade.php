<?php

use Livewire\Volt\Component;

use App\View\QuestionTrait;

new class extends Component
{
    use QuestionTrait;

    public string $answer = '';

    public function mount()
    {
        $this->premount();

        $this->answer = $this->question->answer_data['answer'];
    }

    protected function isCorrectAnswer(): bool
    {
        return true;
    }
}; ?>

<div>
    <form>
        @if($solved)
        <div class="flex flex-col space-y-3">
            @if($question->answer_data['type'] === 'show')
            <div class="flex">
                <x-input-label>{{ __('Answer') }}</x-input-label>
            </div>
            <div class="inline" x-init="$nextTick(() => MathJax.typeset([$el]))">
                $ {{ str_contains($answer, ';') ? '\left( ' . $answer . ' \right)' : $answer }} $
            </div>
            @endif

            <x-success-button class="w-72 justify-center"
                            wire:click.prevent="redo"
                            wire:confirm="{{__('Solve again?')}}">
                {{ __('Done') }}
            </x-success-button>
        </div>
        @else
        <div class="flex">
            <x-primary-button wire:loading.remove wire:click.prevent="submitForm" class="w-72 justify-center">
                {{ __('Continue') }}
            </x-primary-button>
            <div class="ml-32" role="status" wire:loading wire:target="submitForm">
                <x-spinner/>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        @endif
    </form>
</div>
