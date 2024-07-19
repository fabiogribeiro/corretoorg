<?php

use Livewire\Volt\Component;
use SymPHP\Parser\Parser;

use App\View\QuestionTrait;

new class extends Component
{
    use QuestionTrait;

    public string $mf_id;

    public function mount()
    {
        $this->mf_id = 'mf-'.$this->question->id;

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
                <math-field read-only> {{ $answer }} </math-field>
            </div>
            <x-success-button class="w-72 justify-center"
                            wire:click.prevent="redo"
                            wire:confirm="{{__('Solve again?')}}">
            </x-success-button>
        </div>
        @else
        <div class="mt-6 space-y-3">
            <div class="flex flex-col space-x-3 sm:flex-row sm:items-center">
                <math-field id="{{ $mf_id }}" class="w-72 border rounded" placeholder="\text{ {{ __('Insert expression') }} }"></math-field>
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
                <x-primary-button wire:loading.remove x-on:click.prevent="($wire.answer = document.getElementById($wire.mf_id).getValue('ascii-math'));$wire.submitForm()" class="w-72 justify-center">
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
