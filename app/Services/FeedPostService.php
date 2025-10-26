<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\FeedPost;
use App\Models\User;
use App\Models\Vote;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Feed post service for community posts management
 * 
 * Handles feed post creation, voting, trending algorithm,
 * and community engagement features.
 * 
 * @package App\Services
 */
final class FeedPostService
{
    /**
     * Get paginated feed posts with trending algorithm
     * 
     * @param int $page
     * @param int $perPage
     * @param string $sort
     * @return LengthAwarePaginator
     */
    public function getFeedPosts(int $page = 1, int $perPage = 20, string $sort = 'trending'): LengthAwarePaginator
    {
        $query = FeedPost::with(['user', 'votes', 'comments'])
            ->withCount(['votes as upvotes_count' => function ($query) {
                $query->where('vote_type', 'up');
            }])
            ->withCount(['votes as downvotes_count' => function ($query) {
                $query->where('vote_type', 'down');
            }])
            ->withCount('comments');

        // Apply sorting
        switch ($sort) {
            case 'trending':
                $query->orderByRaw('(upvotes_count - downvotes_count) / (TIMESTAMPDIFF(HOUR, created_at, NOW()) + 2) DESC');
                break;
            case 'new':
                $query->orderBy('created_at', 'desc');
                break;
            case 'hot':
                $query->orderByRaw('(upvotes_count - downvotes_count) DESC');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Create a new feed post
     * 
     * @param User $user
     * @param array $data
     * @return FeedPost
     */
    public function createPost(User $user, array $data): FeedPost
    {
        $post = FeedPost::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'body' => $data['body'],
            'image_url' => $data['image_url'] ?? null,
            'upvotes' => 0,
            'downvotes' => 0,
            'comments_count' => 0,
        ]);

        // Clear trending cache
        Cache::forget('trending_posts');

        return $post->load('user');
    }

    /**
     * Get single feed post
     * 
     * @param int $id
     * @return FeedPost|null
     */
    public function getPost(int $id): ?FeedPost
    {
        return FeedPost::with(['user', 'votes', 'comments.user'])
            ->withCount(['votes as upvotes_count' => function ($query) {
                $query->where('vote_type', 'up');
            }])
            ->withCount(['votes as downvotes_count' => function ($query) {
                $query->where('vote_type', 'down');
            }])
            ->find($id);
    }

    /**
     * Vote on a feed post
     * 
     * @param User $user
     * @param FeedPost $post
     * @param string $voteType
     * @return array
     */
    public function vote(User $user, FeedPost $post, string $voteType): array
    {
        // Check if user already voted
        $existingVote = Vote::where('user_id', $user->id)
            ->where('voteable_id', $post->id)
            ->where('voteable_type', FeedPost::class)
            ->first();

        DB::transaction(function () use ($user, $post, $voteType, $existingVote) {
            if ($existingVote) {
                // Update existing vote
                if ($existingVote->vote_type !== $voteType) {
                    $existingVote->update(['vote_type' => $voteType]);
                    
                    // Update post vote counts
                    if ($voteType === 'up') {
                        $post->increment('upvotes');
                        $post->decrement('downvotes');
                    } else {
                        $post->increment('downvotes');
                        $post->decrement('upvotes');
                    }
                }
            } else {
                // Create new vote
                Vote::create([
                    'user_id' => $user->id,
                    'voteable_id' => $post->id,
                    'voteable_type' => FeedPost::class,
                    'vote_type' => $voteType,
                ]);

                // Update post vote counts
                if ($voteType === 'up') {
                    $post->increment('upvotes');
                } else {
                    $post->increment('downvotes');
                }
            }
        });

        // Clear trending cache
        Cache::forget('trending_posts');

        return [
            'upvotes' => $post->fresh()->upvotes,
            'downvotes' => $post->fresh()->downvotes,
        ];
    }

    /**
     * Get post comments
     * 
     * @param FeedPost $post
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getComments(FeedPost $post, int $page = 1, int $perPage = 20): LengthAwarePaginator
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
     * @param FeedPost $post
     * @param string $body
     * @return Comment
     */
    public function addComment(User $user, FeedPost $post, string $body): Comment
    {
        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => FeedPost::class,
            'body' => $body,
        ]);

        // Update post comment count
        $post->increment('comments_count');

        return $comment->load('user');
    }

    /**
     * Get trending posts (cached)
     * 
     * @param int $limit
     * @return Collection
     */
    public function getTrendingPosts(int $limit = 10): Collection
    {
        return Cache::remember('trending_posts', 3600, function () use ($limit) {
            return FeedPost::with(['user'])
                ->withCount(['votes as upvotes_count' => function ($query) {
                    $query->where('vote_type', 'up');
                }])
                ->withCount(['votes as downvotes_count' => function ($query) {
                    $query->where('vote_type', 'down');
                }])
                ->orderByRaw('(upvotes_count - downvotes_count) / (TIMESTAMPDIFF(HOUR, created_at, NOW()) + 2) DESC')
                ->limit($limit)
                ->get();
        });
    }
}


