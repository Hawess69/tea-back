<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedPost\StoreFeedPostRequest;
use App\Http\Requests\FeedPost\VoteFeedPostRequest;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\FeedPostResource;
use App\Http\Resources\CommentResource;
use App\Services\FeedPostService;
use App\Services\CommentService;
use App\Services\ImageService;
use App\Models\FeedPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Feed post controller for community posts
 * 
 * Handles feed post CRUD operations, voting, comments,
 * and trending algorithm for community engagement.
 * 
 * @package App\Http\Controllers\Api
 */
final class FeedPostController extends Controller
{
    public function __construct(
        private readonly FeedPostService $feedPostService,
        private readonly CommentService $commentService,
        private readonly ImageService $imageService
    ) {}

    /**
     * Display a listing of feed posts
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('per_page', 20);
            $sort = $request->get('sort', 'trending');

            $posts = $this->feedPostService->getFeedPosts($page, $perPage, $sort);

            return response()->json([
                'posts' => FeedPostResource::collection($posts),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve feed posts',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created feed post
     * 
     * @param StoreFeedPostRequest $request
     * @return JsonResponse
     */
    public function store(StoreFeedPostRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                if ($this->imageService->validateImage($image)) {
                    $data['image_url'] = $this->imageService->uploadPostImage($image, 0, 'feed');
                } else {
                    return response()->json([
                        'message' => 'Invalid image file',
                    ], 422);
                }
            }

            $post = $this->feedPostService->createPost($request->user(), $data);

            return response()->json([
                'message' => 'Feed post created successfully',
                'post' => new FeedPostResource($post),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create feed post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified feed post
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $post = $this->feedPostService->getPost($id);

            if (!$post) {
                return response()->json([
                    'message' => 'Feed post not found',
                ], 404);
            }

            return response()->json([
                'post' => new FeedPostResource($post),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve feed post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Vote on a feed post
     * 
     * @param VoteFeedPostRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function vote(VoteFeedPostRequest $request, int $id): JsonResponse
    {
        try {
            $post = FeedPost::find($id);

            if (!$post) {
                return response()->json([
                    'message' => 'Feed post not found',
                ], 404);
            }

            $result = $this->feedPostService->vote(
                $request->user(),
                $post,
                $request->validated()['vote_type']
            );

            return response()->json([
                'message' => 'Vote recorded successfully',
                'upvotes' => $result['upvotes'],
                'downvotes' => $result['downvotes'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to record vote',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get comments for a feed post
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function comments(Request $request, int $id): JsonResponse
    {
        try {
            $post = FeedPost::find($id);

            if (!$post) {
                return response()->json([
                    'message' => 'Feed post not found',
                ], 404);
            }

            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('per_page', 20);

            $comments = $this->feedPostService->getComments($post, $page, $perPage);

            return response()->json([
                'comments' => CommentResource::collection($comments),
                'pagination' => [
                    'current_page' => $comments->currentPage(),
                    'last_page' => $comments->lastPage(),
                    'per_page' => $comments->perPage(),
                    'total' => $comments->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve comments',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add comment to a feed post
     * 
     * @param StoreCommentRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function addComment(StoreCommentRequest $request, int $id): JsonResponse
    {
        try {
            $post = FeedPost::find($id);

            if (!$post) {
                return response()->json([
                    'message' => 'Feed post not found',
                ], 404);
            }

            $comment = $this->commentService->addComment(
                $request->user(),
                $post,
                $request->validated()['body']
            );

            return response()->json([
                'message' => 'Comment added successfully',
                'comment' => new CommentResource($comment),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
