<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\FeedPost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Feed post policy for authorization
 * 
 * Handles authorization logic for feed post operations
 * including viewing, creating, updating, and deleting.
 * 
 * @package App\Policies
 */
final class FeedPostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any feed posts.
     * 
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->status === 'active';
    }

    /**
     * Determine whether the user can view the feed post.
     * 
     * @param User $user
     * @param FeedPost $feedPost
     * @return bool
     */
    public function view(User $user, FeedPost $feedPost): bool
    {
        return $user->status === 'active';
    }

    /**
     * Determine whether the user can create feed posts.
     * 
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->status === 'active';
    }

    /**
     * Determine whether the user can update the feed post.
     * 
     * @param User $user
     * @param FeedPost $feedPost
     * @return bool
     */
    public function update(User $user, FeedPost $feedPost): bool
    {
        return $user->id === $feedPost->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the feed post.
     * 
     * @param User $user
     * @param FeedPost $feedPost
     * @return bool
     */
    public function delete(User $user, FeedPost $feedPost): bool
    {
        return $user->id === $feedPost->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can vote on the feed post.
     * 
     * @param User $user
     * @param FeedPost $feedPost
     * @return bool
     */
    public function vote(User $user, FeedPost $feedPost): bool
    {
        return $user->status === 'active' && $user->id !== $feedPost->user_id;
    }

    /**
     * Determine whether the user can comment on the feed post.
     * 
     * @param User $user
     * @param FeedPost $feedPost
     * @return bool
     */
    public function comment(User $user, FeedPost $feedPost): bool
    {
        return $user->status === 'active';
    }
}


