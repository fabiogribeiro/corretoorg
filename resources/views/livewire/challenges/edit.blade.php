<?php

use Livewire\Volt\Component;
use App\Models\Challenge;
use App\Models\Question;
use Illuminate\Support\Str;

new class extends Component
{
    public Challenge $challenge;
    public bool $editing = false;
    public string $title = '';
    public string $body = '';
    public string $subject = '';
    public string $statement= '';
    public string $answer = '';

    public function mount()
    {
        $this->title = $this->challenge->title;
        $this->body = $this->challenge->body;
        $this->subject = $this->challenge->subject;
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function save()
    {
        $this->editing = false;
        $this->challenge->update([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'body' => $this->body,
            'subject' => $this->subject
        ]);
    }

    public function newQuestion()
    {
        $question = new Question;
        $question->challenge_id = $this->challenge->id;
        $question->statement = $this->statement;
        $question->answer = $this->answer;
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
                <x-text-input wire:model="title" value="{{ $challenge->title }}" id="title" title="title" type="text" class="mt-1 block w-full" required autofocus autocomplete="title" />
                <x-input-error class="mt-2" :messages="$errors->get('title')" />
            </div>
            <div>
                <x-input-label for="subject" :value="__('Subject')" />
                <x-text-input wire:model="subject" value="{{ $challenge->subject }}" id="subject" subject="subject" type="text" class="mt-1 block w-full" required autofocus autocomplete="subject" />
                <x-input-error class="mt-2" :messages="$errors->get('subject')" />
            </div>
            <div>
                <x-input-label for="body" :value="__('Body')" />
                <x-multiline-input wire:model="body" value="{{ $challenge->body }}" name="body" id="body" cols="30" rows="10" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get('body')" />
            </div>
        </form>
    @endunless 

    
    @unless ($editing)
        <x-primary-button wire:click="edit">
            Edit
        </x-primary-button>
    @else
        <x-primary-button wire:click="save">
            Save
        </x-primary-button>
    @endunless

    <div class="mt-9">
        <h1 class="text-xl font-medium text-gray-900 dark:text-gray-100">
            Questions
        </h1>

        <!-- list questions here -->
        @foreach ($challenge->questions as $question)
            <div class="my-6 space-y-2">
                <div><x-mmd>{{ $question->statement }}</x-mmd></div>
                <p>
                    Answer: {{ $question->answer }}
                </p>
                <x-danger-button wire:click="deleteQuestion({{$question->id}});setTimeout(MathJax.typeset, 250)">Delete</x-danger-button>
            </div>
        @endforeach

        <form wire:submit="newQuestion" class="py-6 space-y-6">
            <div>
                <x-input-label for="question-title" :value="__('Statement')" />
                <x-text-input wire:model="statement" id="question-title" type="text" class="mt-1 block w-full" required autocomplete="question-title" />
                <x-input-error class="mt-2" :messages="$errors->get('question-title')" />
            </div>
            <div class="w-1/2">
                <x-input-label for="answer" :value="__('Answer')" />
                <x-text-input wire:model="answer" id="answer" type="text" class="mt-1 block w-full" required autocomplete="answer" />
                <x-input-error class="mt-2" :messages="$errors->get('answer')" />
            </div>
            <x-primary-button>
                New question
            </x-primary-button>
        </form>
    </div>
</div>