<?php

use Livewire\Volt\Component;

use App\View\QuestionTrait;

new class extends Component
{
    use QuestionTrait;

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

<div class="min-h-11 mt-6">
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

            <x-success-button class="w-44 h-11 justify-center"
                            wire:click.prevent="redo"
                            wire:confirm="{{__('Solve again?')}}">
            </x-success-button>
        </div>
        @else
        <div class="flex">
            <x-primary-button wire:loading.remove wire:click.prevent="submitForm" class="w-44 h-11 justify-center">
                {{ __('Solved') }}
            </x-primary-button>
            <div class="w-44 mt-1.5" role="status" wire:loading wire:target="submitForm">
                    <div class="flex justify-center">
                        <x-spinner/>
                        <span class="sr-only">Loading...</span>
                    </div>
            </div>
        </div>
        @endif
    </form>
</div>
