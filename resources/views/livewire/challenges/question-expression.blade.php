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
        $state = array_fill(0, $n, false);

        $parser = new Parser();

        $i = 0;
        for (; $i < $n; $i++) {
            try {
                $answer = $parser->parse($answers[$i])->flatten()->simplify();
                $input = $parser->parse($inputs[$i])->flatten()->simplify();

                $state[$i] = $answer->equals($input, 1e-7);
            }
            catch (Exception $e) {
                // $state[$i] stays false
            }
        }

        if (in_array(false, $state)) {
            if ($n > 1)
                // This function should only check for correctness, a bit messy.
                $this->dispatch('set-prompts', id: $this->mf_id, state: $state);

            return false;
        }

        return true;
    }
}; ?>

<div class="w-full sm:w-3/4 lg:w-1/2">
    <form>
        <div class="flex">
            <x-input-label>{{ __('Answer') }}</x-input-label>
        </div>
        @if ($solved)
        <div class="mt-3 flex flex-col items-center space-y-9 sm:flex-row sm:space-y-0 sm:space-x-3">
            <math-field x-init="setMFAnswer('{{$mf_id}}', '{{$answer}}')" id="{{ $mf_id }}" class="w-full py-[5px] items-center border rounded" read-only>
                {{ $question->answer_data['template'] ?? ''}}
            </math-field>
            <x-success-button class="w-44 sm:w-60 h-11"
                            wire:click.prevent="redo"
                            wire:confirm="{{__('Solve again?')}}">
            </x-success-button>
        </div>
        @else
        <div class="mt-3 flex flex-col items-center space-y-9 sm:flex-row sm:space-y-0 sm:space-x-3">
        @if($question->answer_data['template'] ?? false)
            <math-field id="{{ $mf_id }}" class="w-full border rounded" read-only>
                {{ $question->answer_data['template'] }}
            </math-field>
        @else
            <math-field id="{{ $mf_id }}" class="w-full border rounded" placeholder="R=?"></math-field>
        @endif
            <div class="flex">
                <x-primary-button wire:loading.remove x-on:click.prevent="($wire.answer = getAnswerFromMF($wire.mf_id));$wire.submitForm()" class="w-44 h-11">
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
