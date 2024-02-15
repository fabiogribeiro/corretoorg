<?php

use Livewire\Volt\Component;
use Illuminate\Support\Str;

use App\Models\Challenge;
use App\Models\Question;

new class extends Component
{
    public Challenge $challenge;
    public bool $editing = false;
    public ?Question $editing_question = null;
    public string $title = '';
    public string $body = '';
    public string $subject = '';
    public string $stage;
    public string $statement = '';
    public string $answer = '';
    public string $edit_statement = '';
    public string $edit_explanation = '';
    public string $edit_answer = '';
    public string $edit_type = '';
    public string $edit_options = '';

    public function mount()
    {
        $this->title = $this->challenge->title;
        $this->body = $this->challenge->body;
        $this->subject = $this->challenge->subject;
        $this->stage = $this->challenge->stage;
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function editQuestion(Question $question)
    {
        $this->editing_question = $question;
        $this->edit_statement = $question->statement;
        $this->edit_answer = $question->answer_data['answer'];
        $this->edit_type = $question->answer_data['type'];
        $this->edit_options = implode(';', $question->answer_data['options']);
        $this->edit_explanation = $question->explanation ?: '';
    }

    public function save()
    {
        $this->editing = false;
        $this->challenge->update([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'body' => $this->body,
            'subject' => $this->subject,
            'stage' => $this->stage
        ]);
    }

    public function saveQuestion()
    {
        $this->editing_question->update([
            'statement' => $this->edit_statement,
            'explanation' => $this->edit_explanation ?: null,
            'answer_data' => [
                'type' => $this->edit_type,
                'answer' => $this->edit_answer,
                'options' => array_filter(explode(';', $this->edit_options))
            ]
        ]);

        $this->editing_question = null;
    }

    public function newQuestion()
    {
        $question = new Question;
        $question->challenge_id = $this->challenge->id;
        $question->statement = $this->statement;
        $question->answer_data = [];
        $question->answer_data['answer'] = $this->answer;
        $question->answer_data['type'] = 'multiple-choice';
        $question->answer_data['options'] = [];
        $question->save();

        $this->statement = '';
        $this->answer = '';
    }

    public function deleteQuestion($id)
    {
        Question::destroy($id);
    }
}; ?>

<div>
    @unless ($editing)
        <x-mmd>{{ $challenge->body }}</x-mmd>
    @else
        <form class="space-y-6 mb-6">
            <div class="w-1/2">
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input wire:model="title" :value="$challenge->title" id="title" title="title" type="text" class="mt-1 block w-full" required autofocus autocomplete="title" />
                <x-input-error class="mt-2" :messages="$errors->get('title')" />
            </div>
            <div>
                <x-input-label for="subject" :value="__('Subject')" />
                <x-text-input wire:model="subject" :value="$challenge->subject" id="subject" subject="subject" type="text" class="mt-1 block w-full" required autofocus autocomplete="subject" />
                <x-input-error class="mt-2" :messages="$errors->get('subject')" />
            </div>
            <div>
                <x-input-label for="stage" :value="__('Stage')" />
                <select wire:model="stage" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="dev">{{ __('dev') }}</option>
                    <option value="prod">{{ __('prod') }}</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('question-type')" />
            </div>
            <div>
                <x-input-label for="body" :value="__('Body')" />
                <x-multiline-input wire:model.live="body" :value="$challenge->body" name="body" id="body" cols="30" rows="10" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get('body')" />
            </div>
        </form>
    @endunless 

    <div class="py-4">
        @unless ($editing)
        <x-primary-button class="mt-9" wire:click="edit">{{ __('Edit') }}</x-primary-button>
        @else
        <div>
            <x-secondary-button wire:click.prevent="$dispatch('open-modal', 'preview-modal')">{{ __('Preview') }}</x-secondary-button>
            <x-primary-button wire:click.prevent="save">{{ __('Save') }}</x-primary-button>
        </div>
        @endunless

        <div @open-modal.window="MathJax.typeset([$el])">
            <x-modal name="preview-modal">
                <div class="p-4">
                    <x-mmd >{{ $body }}</x-mmd>
                </div>
            </x-modal>
        </div>
    </div>

    <div class="mt-9">
        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">
            {{ __('Questions') }}
        </h1>

        <!-- list questions here -->
        @foreach ($challenge->questions->sortBy('statement', SORT_NATURAL) as $question)
            @if ($editing_question?->id === $question->id)
                <div class="space-y-6">
                    <div>
                        <x-input-label for="question-title" :value="__('Statement')" />
                        <x-multiline-input wire:model="edit_statement" :value="$question->statement" id="question-title" type="text" class="mt-1 block w-full" rows="14" required autocomplete="question-title" />
                        <x-input-error class="mt-2" :messages="$errors->get('question-statement')" />
                    </div>
                    <div class="w-1/2">
                        <x-input-label for="answer" :value="__('Answer')" />
                        <x-text-input wire:model="edit_answer" id="answer" type="text" class="mt-1 block w-full" autocomplete="answer" />
                        <x-input-error class="mt-2" :messages="$errors->get('answer')" />
                    </div>
                    <div>
                        <x-input-label for="question-type" :value="__('Question type')" />
                        <select wire:model="edit_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="multiple-choice">{{ __('Multiple choice') }}</option>
                            <option value="numeric">{{ __('Numeric') }}</option>
                            <option value="expression">{{ __('Expression') }}</option>
                            <option value="show">{{ __('Show') }}</option>
                            <option value="empty">{{ __('Empty') }}</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('question-type')" />
                    </div>
                    <div>
                        <x-input-label for="question-options" :value="__('Options')" />
                        <x-text-input wire:model="edit_options" id="question-options" type="text" class="mt-1 block w-full" required/>
                        <x-input-error class="mt-2" :messages="$errors->get('question-options')" />
                    </div>
                    <div>
                        <x-input-label for="question-explanation" :value="__('Explanation')" />
                        <x-multiline-input wire:model="edit_explanation" id="question-explanation" type="text" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('question-explanation')" />
                    </div>
                    <x-primary-button wire:click="saveQuestion">{{ __('Save') }}</x-primary-button>
                </div>
            @else
                <div wire:click="editQuestion({{$question->id}})" class="my-6">
                    <div class="space-y-3">
                        <div><x-mmd>{{ $question->statement }}</x-mmd></div>
                        <p>Answer: {{ $question->answer_data['answer'] }}</p>
                    </div>
                @if ($question->explanation)
                    <div class="mt-9">
                        <h2 class="font-medium text-gray-900 text-medium">{{ __('Explanation') }}</h2>
                        <p class="my-3">{{ $question->explanation }}</p>
                    </div>
                @endif
                </div>
                <x-danger-button wire:click="deleteQuestion({{$question->id}});setTimeout(MathJax.typeset, 250)">{{ __('Delete') }}</x-danger-button>
            @endif
        @endforeach

        <div class="mt-6">
            <h2 class="text-xl font-bold text-gray-900">{{ __('New question') }}</h2>
            <form wire:submit="newQuestion" class="py-6 space-y-6">
                <div>
                    <x-input-label for="question-title" :value="__('Statement')" />
                    <x-multiline-input wire:model="statement" id="question-title" type="text" class="mt-1 block w-full" required autocomplete="question-title" />
                    <x-input-error class="mt-2" :messages="$errors->get('question-title')" />
                </div>
                <div class="w-1/2">
                    <x-input-label for="answer" :value="__('Answer')" />
                    <x-text-input wire:model="answer" id="answer" type="text" class="mt-1 block w-full" autocomplete="answer" />
                    <x-input-error class="mt-2" :messages="$errors->get('answer')" />
                </div>
                <x-primary-button>{{ __('New question') }}</x-primary-button>
            </form>
        </div>
    </div>
</div>
