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

<div>
    <form>
        <div class="flex">
            <x-input-label>{{ __('Answer') }}</x-input-label>
        </div>
        @if ($solved)
        <div class="mt-6 flex flex-col space-y-3">
            <div class="flex items-center h-10">
                <math-field x-init="setMFAnswer('{{$mf_id}}', '{{$answer}}')" id="{{ $mf_id }}" class="w-72" read-only>
                    {{ $question->answer_data['template'] ?? ''}}
                </math-field>
            </div>
            <x-success-button class="w-72 justify-center"
                            wire:click.prevent="redo"
                            wire:confirm="{{__('Solve again?')}}">
            </x-success-button>
        </div>
        @else
        <div class="mt-6 space-y-3">
            <div class="flex flex-col space-x-3 sm:flex-row sm:items-center">
            @if($question->answer_data['template'] ?? false)
                <math-field id="{{ $mf_id }}" class="w-72 border rounded" read-only>
                    {{ $question->answer_data['template'] }}
                </math-field>
            @else
                <math-field id="{{ $mf_id }}" class="w-72 border rounded" placeholder="R=?"></math-field>
            @endif
            </div>
            <div class="flex">
                <x-primary-button wire:loading.remove x-on:click.prevent="($wire.answer = getAnswerFromMF($wire.mf_id));$wire.submitForm()" class="w-72 justify-center">
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
