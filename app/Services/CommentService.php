<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Comment service for managing comments on posts
 * 
 * Handles comment creation, retrieval, and management
 * for both feed posts and men posts.
 * 
 * @package App\Services
 */
final class CommentService
{
    /**
     * Get comments for a post
     * 
     * @param Model $post
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getComments(Model $post, int $page = 1, int $perPage = 20): LengthAwarePaginator
    {
        return $post->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Add comment to a post
     * 
     * @param User $user
     * @param Model $post
     * @param string $body
     * @return Comment
     */
    public function addComment(User $user, Model $post, string $body): Comment
    {
        $postType = $this->getPostType($post);
        
        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'post_type' => $postType,
            'body' => $body,
        ]);

        return $comment->load('user');
    }

    /**
     * Update a comment
     * 
     * @param User $user
     * @param Comment $comment
     * @param string $body
     * @return Comment
     */
    public function updateComment(User $user, Comment $comment, string $body): Comment
    {
        // Check if user owns the comment
        if ($comment->user_id !== $user->id) {
            throw new \Exception('Unauthorized to update this comment');
        }

        $comment->update(['body' => $body]);
        return $comment->fresh()->load('user');
    }

    /**
     * Delete a comment
     * 
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function deleteComment(User $user, Comment $comment): bool
    {
        // Check if user owns the comment or is admin
        if ($comment->user_id !== $user->id && $user->role !== 'admin') {
            throw new \Exception('Unauthorized to delete this comment');
        }

        return $comment->delete();
    }

    /**
     * Get comment by ID
     * 
     * @param int $id
     * @return Comment|null
     */
    public function getComment(int $id): ?Comment
    {
        return Comment::with('user')->find($id);
    }

    /**
     * Get post type for comment creation
     * 
     * @param Model $post
     * @return string
     */
    private function getPostType(Model $post): string
    {
        $className = class_basename($post);
        
        return match ($className) {
            'FeedPost' => 'feed',
            'MenPost' => 'men',
            default => throw new \Exception('Unsupported post type'),
        };
    }
}


