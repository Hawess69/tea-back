<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\MenPost;
use App\Models\User;
use App\Models\Flag;
use App\Models\Comment;
use App\Models\Alert;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Men post service for review and warning system
 * 
 * Handles men post creation, flagging, alert matching,
 * and privacy protection features.
 * 
 * @package App\Services
 */
final class MenPostService
{
    /**
     * Get paginated men posts with filters
     * 
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getMenPosts(int $page = 1, int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        $query = MenPost::with(['user', 'flags', 'comments'])
            ->withCount(['flags as red_flags_count' => function ($query) {
                $query->where('flag_type', 'red');
            }])
            ->withCount(['flags as green_flags_count' => function ($query) {
                $query->where('flag_type', 'green');
            }])
            ->withCount(['flags as neutral_flags_count' => function ($query) {
                $query->where('flag_type', 'neutral');
            }])
            ->withCount('comments');

        // Apply filters
        if (isset($filters['city'])) {
            $query->where('city', 'like', '%' . $filters['city'] . '%');
        }

        if (isset($filters['tags'])) {
            $query->whereJsonContains('tags', $filters['tags']);
        }

        if (isset($filters['name'])) {
            $query->where('full_name', 'like', '%' . $filters['name'] . '%');
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Create a new men post
     * 
     * @param User $user
     * @param array $data
     * @return MenPost
     */
    public function createPost(User $user, array $data): MenPost
    {
        $post = MenPost::create([
            'user_id' => $user->id,
            'full_name' => $data['full_name'],
            'city' => $data['city'],
            'tags' => $data['tags'] ?? [],
            'caption' => $data['caption'],
            'photo_url' => $data['photo_url'] ?? null,
            'flag_counts' => [
                'red' => 0,
                'green' => 0,
                'neutral' => 0,
            ],
        ]);

        // Process image blurring if photo provided
        if ($data['photo_url']) {
            // This would be handled by a queue job in production
            $this->processImageBlurring($post);
        }

        // Check for alert matches
        $this->checkAlertMatches($post);

        return $post->load('user');
    }

    /**
     * Get single men post
     * 
     * @param int $id
     * @return MenPost|null
     */
    public function getPost(int $id): ?MenPost
    {
        return MenPost::with(['user', 'flags', 'comments.user'])
            ->withCount(['flags as red_flags_count' => function ($query) {
                $query->where('flag_type', 'red');
            }])
            ->withCount(['flags as green_flags_count' => function ($query) {
                $query->where('flag_type', 'green');
            }])
            ->withCount(['flags as neutral_flags_count' => function ($query) {
                $query->where('flag_type', 'neutral');
            }])
            ->find($id);
    }

    /**
     * Flag a men post
     * 
     * @param User $user
     * @param MenPost $post
     * @param string $flagType
     * @return array
     */
    public function flag(User $user, MenPost $post, string $flagType): array
    {
        // Check if user already flagged
        $existingFlag = Flag::where('user_id', $user->id)
            ->where('flagable_id', $post->id)
            ->where('flagable_type', MenPost::class)
            ->first();

        DB::transaction(function () use ($user, $post, $flagType, $existingFlag) {
            if ($existingFlag) {
                // Update existing flag
                if ($existingFlag->flag_type !== $flagType) {
                    $existingFlag->update(['flag_type' => $flagType]);
                    
                    // Update post flag counts
                    $this->updateFlagCounts($post, $existingFlag->flag_type, $flagType);
                }
            } else {
                // Create new flag
                Flag::create([
                    'user_id' => $user->id,
                    'flagable_id' => $post->id,
                    'flagable_type' => MenPost::class,
                    'flag_type' => $flagType,
                ]);

                // Update post flag counts
                $this->incrementFlagCount($post, $flagType);
            }
        });

        return [
            'red_flags' => $post->fresh()->flag_counts['red'] ?? 0,
            'green_flags' => $post->fresh()->flag_counts['green'] ?? 0,
            'neutral_flags' => $post->fresh()->flag_counts['neutral'] ?? 0,
        ];
    }

    /**
     * Get post comments
     * 
     * @param MenPost $post
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getComments(MenPost $post, int $page = 1, int $perPage = 20): LengthAwarePaginator
    {
        return $post->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Add comment to post
     * 
     * @param User $user
     * @param MenPost $post
     * @param string $body
     * @return Comment
     */
    public function addComment(User $user, MenPost $post, string $body): Comment
    {
        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => MenPost::class,
            'body' => $body,
        ]);

        return $comment->load('user');
    }

    /**
     * Check for alert matches and send notifications
     * 
     * @param MenPost $post
     * @return void
     */
    private function checkAlertMatches(MenPost $post): void
    {
        $alerts = Alert::where('is_active', true)
            ->where('name_to_track', 'like', '%' . $post->full_name . '%')
            ->get();

        foreach ($alerts as $alert) {
            // This would trigger a queue job in production
            Log::info('Alert match found', [
                'alert_id' => $alert->id,
                'post_id' => $post->id,
                'name' => $post->full_name,
            ]);
        }
    }

    /**
     * Process image blurring for privacy
     * 
     * @param MenPost $post
     * @return void
     */
    private function processImageBlurring(MenPost $post): void
    {
        // This would be handled by a queue job in production
        Log::info('Image blurring requested', [
            'post_id' => $post->id,
            'photo_url' => $post->photo_url,
        ]);
    }

    /**
     * Update flag counts when flag type changes
     * 
     * @param MenPost $post
     * @param string $oldType
     * @param string $newType
     * @return void
     */
    private function updateFlagCounts(MenPost $post, string $oldType, string $newType): void
    {
        $flagCounts = $post->flag_counts;
        
        // Decrement old type
        if (isset($flagCounts[$oldType]) && $flagCounts[$oldType] > 0) {
            $flagCounts[$oldType]--;
        }
        
        // Increment new type
        $flagCounts[$newType] = ($flagCounts[$newType] ?? 0) + 1;
        
        $post->update(['flag_counts' => $flagCounts]);
    }

    /**
     * Increment flag count for new flag
     * 
     * @param MenPost $post
     * @param string $flagType
     * @return void
     */
    private function incrementFlagCount(MenPost $post, string $flagType): void
    {
        $flagCounts = $post->flag_counts;
        $flagCounts[$flagType] = ($flagCounts[$flagType] ?? 0) + 1;
        
        $post->update(['flag_counts' => $flagCounts]);
    }
}


