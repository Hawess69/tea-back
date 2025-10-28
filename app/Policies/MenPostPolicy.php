<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MenPost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Men post policy for authorization
 * 
 * Handles authorization logic for men post operations
 * including viewing, creating, flagging, and moderation.
 * 
 * @package App\Policies
 */
final class MenPostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any men posts.
     * 
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->status === 'active';
    }

    /**
     * Determine whether the user can view the men post.
     * 
     * @param User $user
     * @param MenPost $menPost
     * @return bool
     */
    public function view(User $user, MenPost $menPost): bool
    {
        return $user->status === 'active';
    }

    /**
     * Determine whether the user can create men posts.
     * 
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->status === 'active';
    }

    /**
     * Determine whether the user can update the men post.
     * 
     * @param User $user
     * @param MenPost $menPost
     * @return bool
     */
    public function update(User $user, MenPost $menPost): bool
    {
        return $user->id === $menPost->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the men post.
     * 
     * @param User $user
     * @param MenPost $menPost
     * @return bool
     */
    public function delete(User $user, MenPost $menPost): bool
    {
        return $user->id === $menPost->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can flag the men post.
     * 
     * @param User $user
     * @param MenPost $menPost
     * @return bool
     */
    public function flag(User $user, MenPost $menPost): bool
    {
        return $user->status === 'active' && $user->id !== $menPost->user_id;
    }

    /**
     * Determine whether the user can comment on the men post.
     * 
     * @param User $user
     * @param MenPost $menPost
     * @return bool
     */
    public function comment(User $user, MenPost $menPost): bool
    {
        return $user->status === 'active';
    }

    /**
     * Determine whether the user can moderate men posts.
     * 
     * @param User $user
     * @return bool
     */
    public function moderate(User $user): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }
}


