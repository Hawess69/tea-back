<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Admin policy for authorization
 * 
 * Handles authorization logic for admin-only operations
 * including user management, content moderation, and analytics.
 * 
 * @package App\Policies
 */
final class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can access admin panel.
     * 
     * @param User $user
     * @return bool
     */
    public function accessAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can manage users.
     * 
     * @param User $user
     * @return bool
     */
    public function manageUsers(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can moderate content.
     * 
     * @param User $user
     * @return bool
     */
    public function moderateContent(User $user): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }

    /**
     * Determine whether the user can view analytics.
     * 
     * @param User $user
     * @return bool
     */
    public function viewAnalytics(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can manage events.
     * 
     * @param User $user
     * @return bool
     */
    public function manageEvents(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can send notifications.
     * 
     * @param User $user
     * @return bool
     */
    public function sendNotifications(User $user): bool
    {
        return $user->role === 'admin';
    }
}


