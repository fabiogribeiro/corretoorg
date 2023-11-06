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
}; ?>


<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg space-y-6">
    <h2 class="text-2xl font-bold text-gray-700">{{ __('Discussion') }}</h2>
    <ul>
    @foreach($comments as $comment)
        <li>
            {{ $comment->text }}
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
