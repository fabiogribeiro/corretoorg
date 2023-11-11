<?php

use Livewire\Volt\Component;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Challenge;
use App\Models\Comment;

new class extends Component
{
    public Challenge $challenge;
    public Collection $comments;
    public string $text;

    public function newComment()
    {
        $validated = $this->validate(['text' => 'required|min:3']);

        $comment = new Comment;
        $comment->text = $validated['text'];
        $comment->user_id = auth()->user()->id;
        $comment->challenge_id = $this->challenge->id;
        $comment->save();

        $this->comments[] = $comment;
    }

    public function deleteComment(Comment $comment)
    {
        if (auth()->user()->cannot('delete', $comment)) return;

        $this->comments = $this->comments->except($comment->id);
        $comment->delete();
    }
}; ?>


<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg space-y-6">
    <h2 class="text-2xl font-bold text-gray-700">{{ __('Discussion') }}</h2>
    <ul class="divide-y divide-gray-100">
    @foreach($comments as $comment)
        <li class="flex justify-between items-center py-3" wire:key="{{ $comment->id }}">
            <div class="shrink-0">
                <p class="text-justify font-semibold text-gray-900 max-w-prose" wire:ignore>
                    {{ $comment->text }}
                </p>
                <p class="mt-1 text-sm text-gray-500">{{ $comment->author->name }}</p>
            </div>
            <div class="flex inline items-center space-x-3">
            @can('delete', $comment)
                <x-secondary-button
                    wire:click="deleteComment({{ $comment->id }})"
                    wire:confirm="{{ __('Delete comment?') }}">
                    {{ __('Delete') }}
                </x-secondary-button>
            @endcan
                <p class="text-gray-500 text-sm">{{ $comment->created_at->format('H:i, d-m-Y') }}</p>
            </div>
        </li>
    @endforeach
    </ul>
    <form wire:submit="newComment">
        <div class="inline-flex space-x-3 w-full">
            <x-text-input wire:model="text" type="text" class="grow" placeholder="New comment" required />
            <x-primary-button>{{ __('Submit') }}</x-primary-button>
        </div>
    </form>
</div>
