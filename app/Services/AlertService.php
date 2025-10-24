<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Alert;
use App\Models\User;
use App\Models\MenPost;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Alert service for name tracking and notifications
 * 
 * Handles alert creation, matching, and notification
 * processing for men post monitoring.
 * 
 * @package App\Services
 */
final class AlertService
{
    /**
     * Get user alerts
     * 
     * @param User $user
     * @return Collection
     */
    public function getUserAlerts(User $user): Collection
    {
        return $user->alerts()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create a new alert
     * 
     * @param User $user
     * @param array $data
     * @return Alert
     */
    public function createAlert(User $user, array $data): Alert
    {
        $alert = Alert::create([
            'user_id' => $user->id,
            'name_to_track' => $data['name_to_track'],
            'is_active' => true,
        ]);

        return $alert;
    }

    /**
     * Delete an alert
     * 
     * @param User $user
     * @param int $alertId
     * @return bool
     */
    public function deleteAlert(User $user, int $alertId): bool
    {
        $alert = Alert::where('user_id', $user->id)
            ->where('id', $alertId)
            ->first();

        if (!$alert) {
            throw new \Exception('Alert not found');
        }

        return $alert->delete();
    }

    /**
     * Toggle alert status
     * 
     * @param User $user
     * @param int $alertId
     * @return Alert
     */
    public function toggleAlert(User $user, int $alertId): Alert
    {
        $alert = Alert::where('user_id', $user->id)
            ->where('id', $alertId)
            ->first();

        if (!$alert) {
            throw new \Exception('Alert not found');
        }

        $alert->update(['is_active' => !$alert->is_active]);
        return $alert->fresh();
    }

    /**
     * Check for alert matches in a men post
     * 
     * @param MenPost $post
     * @return array
     */
    public function checkAlertMatches(MenPost $post): array
    {
        $matches = [];
        
        $alerts = Alert::where('is_active', true)
            ->where('name_to_track', 'like', '%' . $post->full_name . '%')
            ->get();

        foreach ($alerts as $alert) {
            $matches[] = [
                'alert_id' => $alert->id,
                'user_id' => $alert->user_id,
                'name_to_track' => $alert->name_to_track,
                'post_id' => $post->id,
                'post_name' => $post->full_name,
            ];
        }

        return $matches;
    }

    /**
     * Process alert matches and send notifications
     * 
     * @param MenPost $post
     * @return void
     */
    public function processAlertMatches(MenPost $post): void
    {
        $matches = $this->checkAlertMatches($post);

        foreach ($matches as $match) {
            // Log the match for now - in production this would trigger notifications
            Log::info('Alert match found', [
                'alert_id' => $match['alert_id'],
                'user_id' => $match['user_id'],
                'post_id' => $match['post_id'],
                'name_to_track' => $match['name_to_track'],
                'post_name' => $match['post_name'],
            ]);

            // In production, this would dispatch a notification job
            // dispatch(new SendAlertNotificationJob($match));
        }
    }

    /**
     * Get alert statistics
     * 
     * @return array
     */
    public function getAlertStats(): array
    {
        return [
            'total_alerts' => Alert::count(),
            'active_alerts' => Alert::where('is_active', true)->count(),
            'inactive_alerts' => Alert::where('is_active', false)->count(),
        ];
    }
}


