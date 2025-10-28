<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService
    ) {}

    /**
     * Update the specified comment
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
                    'error' => "Comment with ID {$id} does not exist",
                ], 404);
            }

            $updatedComment = $this->commentService->updateComment(
                $request->user(),
                $comment,
                $request->input('body')
            );

            return response()->json([
                'message' => 'Comment updated successfully',
                'comment' => [
                    'id' => $updatedComment->id,
                    'body' => $updatedComment->body,
                    'user' => [
                        'name' => $updatedComment->user->name,
                    ],
                    'created_at' => $updatedComment->created_at->diffForHumans(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Comment update failed', [
                'comment_id' => $id,
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to update comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified comment
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
