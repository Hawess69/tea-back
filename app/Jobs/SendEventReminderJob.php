<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Event;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Send event reminder job
 * 
 * Handles background processing of event reminders
 * for upcoming events and notifications.
 * 
 * @package App\Jobs
 */
final class SendEventReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     * 
     * @param NotificationService $notificationService
     * @return void
     */
    public function handle(NotificationService $notificationService): void
    {
        try {
            Log::info('Starting event reminder processing');

            // Get events happening in the next 24 hours
            $upcomingEvents = Event::where('event_date', '>', now())
                ->where('event_date', '<=', now()->addDay())
                ->get();

            foreach ($upcomingEvents as $event) {
                // Get users who might be interested (this would be more sophisticated in production)
                $users = $this->getInterestedUsers($event);

                foreach ($users as $user) {
                    $notificationService->sendEventReminder(
                        $user,
                        $event->title,
                        $event->event_date->format('Y-m-d H:i')
                    );
                }
            }

            Log::info('Event reminder processing completed', [
                'events_count' => $upcomingEvents->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Event reminder processing failed', [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get users interested in the event
     * 
     * @param Event $event
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getInterestedUsers(Event $event): \Illuminate\Database\Eloquent\Collection
    {
        // In production, this would be more sophisticated
        // For now, return all active users
        return \App\Models\User::where('status', 'active')->get();
    }

    /**
     * Handle a job failure.
     * 
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendEventReminderJob failed', [
            'error' => $exception->getMessage(),
        ]);
    }
}


