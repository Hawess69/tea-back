<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Comment controller for comment management
 * 
 * Handles comment CRUD operations for both
 * feed posts and men posts.
 * 
 * @package App\Http\Controllers\Api
 */
final class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService
    ) {}

    /**
     * Display the specified comment
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $comment = $this->commentService->getComment($id);

            if (!$comment) {
                return response()->json([
                    'message' => 'Comment not found',
                ], 404);
            }

            return response()->json([
                'comment' => new CommentResource($comment),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified comment
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'body' => 'required|string|max:1000|min:1',
            ]);

            $comment = Comment::find($id);

            if (!$comment) {
                return response()->json([
                    'message' => 'Comment not found',
                ], 404);
            }

            $updatedComment = $this->commentService->updateComment(
                $request->user(),
                $comment,
                $request->validated()['body']
            );

            return response()->json([
                'message' => 'Comment updated successfully',
                'comment' => new CommentResource($updatedComment),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified comment
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $comment = Comment::find($id);

            if (!$comment) {
                return response()->json([
                    'message' => 'Comment not found',
                ], 404);
            }

            $deleted = $this->commentService->deleteComment($request->user(), $comment);

            if (!$deleted) {
                return response()->json([
                    'message' => 'Failed to delete comment',
                ], 500);
            }

            return response()->json([
                'message' => 'Comment deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}