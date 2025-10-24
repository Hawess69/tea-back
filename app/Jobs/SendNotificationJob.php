<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Send notification job for push notifications
 * 
 * Handles background processing of push notifications
 * via Expo Push API and email notifications.
 * 
 * @package App\Jobs
 */
final class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * 
     * @param User $user
     * @param string $title
     * @param string $body
     * @param string $type
     * @param string $sentVia
     */
    public function __construct(
        private readonly User $user,
        private readonly string $title,
        private readonly string $body,
        private readonly string $type = 'general',
        private readonly string $sentVia = 'expo'
    ) {}

    /**
     * Execute the job.
     * 
     * @param NotificationService $notificationService
     * @return void
     */
    public function handle(NotificationService $notificationService): void
    {
        try {
            Log::info('Sending notification', [
                'user_id' => $this->user->id,
                'title' => $this->title,
                'type' => $this->type,
                'sent_via' => $this->sentVia,
            ]);

            // Create notification record
            $notification = $notificationService->createNotification(
                $this->user,
                $this->title,
                $this->body,
                $this->type,
                $this->sentVia
            );

            // Send via appropriate channel
            if ($this->sentVia === 'expo') {
                $notificationService->sendExpoPushNotification(
                    $this->user,
                    $this->title,
                    $this->body
                );
            } elseif ($this->sentVia === 'email') {
                $notificationService->sendEmailNotification(
                    $this->user,
                    $this->title,
                    $this->body
                );
            }

            Log::info('Notification sent successfully', [
                'notification_id' => $notification->id,
                'user_id' => $this->user->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Notification sending failed', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     * 
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendNotificationJob failed', [
            'user_id' => $this->user->id,
            'error' => $exception->getMessage(),
        ]);
    }
}


