<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
    *  Admins can change comments.
    */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) return true;

        return null;
    }

    /**
     * Determine if the given user can create posts.
     */
    public function create(User $user): bool
    {
        return boolval($user->email_verified_at);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id == $comment->author->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id == $comment->author->id;
    }
}
