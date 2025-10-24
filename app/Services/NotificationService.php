<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

/**
 * Notification service for push and email notifications
 * 
 * Handles notification creation, sending, and management
 * for various platform events.
 * 
 * @package App\Services
 */
final class NotificationService
{
    /**
     * Get user notifications
     * 
     * @param User $user
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUserNotifications(User $user, int $page = 1, int $perPage = 20): LengthAwarePaginator
    {
        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Create a notification
     * 
     * @param User $user
     * @param string $title
     * @param string $body
     * @param string $type
     * @param string $sentVia
     * @return Notification
     */
    public function createNotification(
        User $user,
        string $title,
        string $body,
        string $type = 'general',
        string $sentVia = 'expo'
    ): Notification {
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'sent_via' => $sentVia,
        ]);
    }

    /**
     * Send alert notification
     * 
     * @param User $user
     * @param string $name
     * @param string $postId
     * @return Notification
     */
    public function sendAlertNotification(User $user, string $name, string $postId): Notification
    {
        $title = 'Alert Match Found';
        $body = "A post about {$name} has been created. Check it out!";
        
        return $this->createNotification($user, $title, $body, 'alert', 'expo');
    }

    /**
     * Send comment notification
     * 
     * @param User $user
     * @param string $postTitle
     * @return Notification
     */
    public function sendCommentNotification(User $user, string $postTitle): Notification
    {
        $title = 'New Comment';
        $body = "Someone commented on your post: {$postTitle}";
        
        return $this->createNotification($user, $title, $body, 'comment', 'expo');
    }

    /**
     * Send vote notification
     * 
     * @param User $user
     * @param string $postTitle
     * @param string $voteType
     * @return Notification
     */
    public function sendVoteNotification(User $user, string $postTitle, string $voteType): Notification
    {
        $title = 'New Vote';
        $body = "Someone {$voteType}voted on your post: {$postTitle}";
        
        return $this->createNotification($user, $title, $body, 'vote', 'expo');
    }

    /**
     * Send event reminder
     * 
     * @param User $user
     * @param string $eventTitle
     * @param string $eventDate
     * @return Notification
     */
    public function sendEventReminder(User $user, string $eventTitle, string $eventDate): Notification
    {
        $title = 'Event Reminder';
        $body = "Don't forget about {$eventTitle} on {$eventDate}";
        
        return $this->createNotification($user, $title, $body, 'event', 'expo');
    }

    /**
     * Send broadcast notification to all users
     * 
     * @param string $title
     * @param string $body
     * @param string $type
     * @return int
     */
    public function sendBroadcastNotification(string $title, string $body, string $type = 'general'): int
    {
        $users = User::where('status', 'active')->get();
        $count = 0;

        foreach ($users as $user) {
            $this->createNotification($user, $title, $body, $type, 'expo');
            $count++;
        }

        return $count;
    }

    /**
     * Mark notification as read
     * 
     * @param User $user
     * @param int $notificationId
     * @return bool
     */
    public function markAsRead(User $user, int $notificationId): bool
    {
        $notification = Notification::where('user_id', $user->id)
            ->where('id', $notificationId)
            ->first();

        if (!$notification) {
            return false;
        }

        // Add read_at timestamp if not already set
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return true;
    }

    /**
     * Get notification statistics
     * 
     * @param User $user
     * @return array
     */
    public function getNotificationStats(User $user): array
    {
        return [
            'total' => $user->notifications()->count(),
            'unread' => $user->notifications()->whereNull('read_at')->count(),
            'read' => $user->notifications()->whereNotNull('read_at')->count(),
        ];
    }

    /**
     * Send Expo push notification (placeholder)
     * 
     * @param User $user
     * @param string $title
     * @param string $body
     * @return bool
     */
    public function sendExpoPushNotification(User $user, string $title, string $body): bool
    {
        // This would integrate with Expo Push API in production
        Log::info('Expo push notification sent', [
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
        ]);

        return true;
    }

    /**
     * Send email notification (placeholder)
     * 
     * @param User $user
     * @param string $title
     * @param string $body
     * @return bool
     */
    public function sendEmailNotification(User $user, string $title, string $body): bool
    {
        // This would integrate with Laravel Mail in production
        Log::info('Email notification sent', [
            'user_id' => $user->id,
            'email' => $user->email,
            'title' => $title,
            'body' => $body,
        ]);

        return true;
    }
}


