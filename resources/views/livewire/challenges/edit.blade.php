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
    public string $orderKey;
    public string $description;
    public string $statement = '';
    public string $answer = '';
    public string $edit_statement = '';
    public string $edit_explanation = '';
    public string $edit_answer = '';
    public string $edit_key = '';
    public string $edit_type = '';
    public string $edit_options = '';
    public string $edit_template = '';

    public function mount()
    {
        $this->title = $this->challenge->title;
        $this->body = $this->challenge->body;
        $this->subject = $this->challenge->subject;
        $this->stage = $this->challenge->stage;
        $this->description = $this->challenge->description;
        $this->orderKey = $this->challenge->order_key;
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
        $this->edit_key = $question->order_key;
        $this->edit_type = $question->answer_data['type'];
        $this->edit_options = implode(';', $question->answer_data['options']);
        $this->edit_explanation = $question->explanation ?: '';
        $this->edit_template = $question->answer_data['template'] ?? '';
    }

    public function save()
    {
        $this->editing = false;
        $this->challenge->update([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'body' => $this->body,
            'subject' => $this->subject,
            'stage' => $this->stage,
            'description' => $this->description,
            'order_key' => $this->orderKey
        ]);
    }

    public function saveQuestion()
    {
        $this->editing_question->update([
            'statement' => $this->edit_statement,
            'explanation' => $this->edit_explanation ?: null,
            'order_key' => $this->edit_key,
            'answer_data' => [
                'type' => $this->edit_type,
                'answer' => $this->edit_answer,
                'options' => array_filter(explode(';', $this->edit_options)),
                'template' => $this->edit_template,
            ],
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

    public function delete()
    {
        $this->challenge->delete();
        $this->redirect(route('challenges.index'));
    }

    public function deleteQuestion($id)
    {
        Question::destroy($id);
    }

    public function setDefaultOrder()
    {
        $i = 1;
        foreach ($this->challenge->questions->sortBy('statement', SORT_NATURAL) as $question) {
            $question->order_key = $i++;
            $question->save();
        }
    }

    public function upOrder(Question $question)
    {
        $other = $this->challenge->questions->where('order_key', $question->order_key - 1)->first();
        $other->order_key = $question->order_key;
        $other->save();

        $question->order_key -= 1;
        $question->save();

        return redirect(request()->header('Referer'));
    }

    public function downOrder(Question $question)
    {
        $other = $this->challenge->questions->where('order_key', $question->order_key + 1)->first();
        $other->order_key = $question->order_key;
        $other->save();

        $question->order_key += 1;
        $question->save();

        return redirect(request()->header('Referer'));
    }

    public function merge(Question $question)
    {
        $other = $this->challenge->questions->where('order_key', $question->order_key + 1)->first();
        $other->statement = $question->statement."\n\n".$other->statement;
        $other->save();

        $question->delete();

        return redirect(request()->header('Referer'));
    }
}; ?>

<div>
    @unless ($editing)
        <x-mmd :content="$challenge->body"/>
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
                <select wire:model="stage" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="dev">{{ __('dev') }}</option>
                    <option value="prod">{{ __('prod') }}</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('stage')" />
            </div>
            <div>
                <x-input-label for="description" :value="__('Description')" />
                <x-multiline-input wire:model="description" :value="$challenge->description" id="description" rows="2" class="mt-1 block w-full"/>
                <x-input-error class="mt-2" :messages="$errors->get('description')" />
            </div>
            <div>
                <x-input-label for="orderKey" :value="__('Order key')" />
                <x-text-input wire:model="orderKey" :value="$challenge->order_key" id="orderKey" type="text" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get('orderKey')" />
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
        <x-danger-button class="mt-9" wire:click.prevent="delete" wire:confirm="{{ __('Delete challenge?') }}">
            {{ __('Delete') }}
        </x-danger-button>
        @else
        <div>
            <x-secondary-button wire:click.prevent="$dispatch('open-modal', 'preview-modal')">{{ __('Preview') }}</x-secondary-button>
            <x-primary-button wire:click.prevent="save">{{ __('Save') }}</x-primary-button>
        </div>
        @endunless

        <div @open-modal.window="MathJax.typeset([$el])">
            <x-modal name="preview-modal">
                <div class="p-4">
                    <x-mmd :content="$body"/>
                </div>
            </x-modal>
        </div>
    </div>

    <div class="mt-9">
        <div class="flex flex-row space-x-6 items-center">
            <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                {{ trans_choice('Questions', 2) }}
            </h1>
            <a href="#" class="underline text-gray-500" wire:click.prevent="setDefaultOrder" wire:confirm>{{ 'Set default order' }}</a>
        </div>

        <!-- list questions here -->
        @foreach ($challenge->questions->sortBy('order_key') as $question)
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
                    <div class="w-1/2">
                        <x-input-label for="order_key" :value="__('Order key')" />
                        <x-text-input wire:model="edit_key" id="order_key" type="text" class="mt-1 block w-full" autocomplete="answer" />
                        <x-input-error class="mt-2" :messages="$errors->get('order_key')" />
                    </div>
                    <div>
                        <x-input-label for="question-type" :value="__('Question type')" />
                        <select wire:model="edit_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                        <x-input-label for="template" :value="__('Template')" />
                        <math-field wire:model="edit_template" class="mt-2 w-72 border rounded" id="template"></math-field>
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
                        <div><x-mmd :content="$question->statement"/></div>
                        <p>Answer: {{ $question->answer_data['answer'] }}</p>
                    </div>
                @if ($question->explanation)
                    <div class="mt-9">
                        <h2 class="font-medium text-gray-900 text-medium">{{ __('Explanation') }}</h2>
                        <p class="my-3">{{ $question->explanation }}</p>
                    </div>
                @endif
                </div>
                <x-secondary-button wire:click="upOrder({{$question->id}})">{{ __('Up') }}</x-secondary-button>
                <x-secondary-button wire:click="downOrder({{$question->id}})">{{ __('Down') }}</x-secondary-button>
                <x-secondary-button wire:click="merge({{$question->id}})">{{ __('Merge') }}</x-secondary-button>
                <x-danger-button wire:click="deleteQuestion({{$question->id}});setTimeout(MathJax.typeset, 250)" wire:confirm="{{ __('Delete').'?' }}">{{ __('Delete') }}</x-danger-button>
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
