<?php

use Livewire\Volt\Component;
use SymPHP\Parser\Parser;

use App\View\QuestionTrait;

new class extends Component
{
    use QuestionTrait;

    public string $answer = '';

    public function mount()
    {
        $this->premount();

        if ($this->solved)
            $this->answer = $this->question->answer_data['answer'];
    }

    protected function isCorrectAnswer(): bool
    {
        $answers = explode(';', $this->question->answer_data['answer']);
        $inputs = explode(';', $this->answer);

        $n = count($answers);
        if (count($inputs) !== $n) {
            $this->addError('num_inputs', $n);
            return false;
        }

        $parser = new Parser();

        $i = 0;
        for (; $i < $n; $i++) {
            try {
                $answer = $parser->parse($answers[$i])->flatten()->simplify();
                $input = $parser->parse($inputs[$i])->flatten()->simplify();

                if (!($answer->equals($input, 1e-7)))
                    goto wrongAnswer;
            }
            catch (Exception $e) {
                goto wrongAnswer;
            }
        }

        return true;

        wrongAnswer:

        if ($n > 1)
            $this->addError('input', $i + 1);

        return false;
    }
}; ?>

<div>
    <form>
        <div class="flex">
            <x-input-label>{{ __('Answer') }}</x-input-label>
        </div>
        @if ($solved)
        <div class="mt-6 flex flex-col space-y-3">
            <div class="flex items-center h-10">
                <div class="inline" x-init="$nextTick(() => MathJax.typeset([$el]))">
                    ` {{ str_contains($answer = $question->answer_data['answer'], ';') ? '(' . $answer . ')' : $answer }} `
                </div>
            </div>
            <x-success-button class="w-72 justify-center"
                            wire:click.prevent="redo"
                            wire:confirm="{{__('Solve again?')}}">{{ __('Done') }}</x-success-button>
        </div>
        @else
        <div class="mt-6 space-y-3">
            <div class="flex flex-col space-x-3 sm:flex-row sm:items-center">
                <x-text-input wire:click="unsubmit" wire:model="answer" class="w-72 text-gray-700 {{ $submitted ? 'ring-1 border-red-400 ring-red-400' : '' }}" type="text" placeholder="{{ __('Insert expression') }}"/>
                <div wire:ignore x-init="$watch('$wire.answer', value => {
                    $el.textContent = '` ' + value + ' `';
                    MathJax.typeset([$el]);
                })">
                </div>
            </div>
            @error('num_inputs')
            <div>
                {{ __('validation.expr_input_num', ['num' => $message]) }}
            </div>
            @enderror
            @error('input')
            <div>
                {{ __('validation.expr_input', ['num' => $message]) }}
            </div>
            @enderror
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
