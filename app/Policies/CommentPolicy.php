<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Comment policy for authorization
 * 
 * Handles authorization logic for comment operations
 * including viewing, creating, updating, and deleting.
 * 
 * @package App\Policies
 */
final class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any comments.
     * 
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->status === 'active';
    }

    /**
     * Determine whether the user can view the comment.
     * 
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function view(User $user, Comment $comment): bool
    {
        return $user->status === 'active';
    }

    /**
     * Determine whether the user can create comments.
     * 
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->status === 'active';
    }

    /**
     * Determine whether the user can update the comment.
     * 
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the comment.
     * 
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || in_array($user->role, ['admin', 'moderator']);
    }
}


