<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenPost\StoreMenPostRequest;
use App\Http\Requests\MenPost\FlagMenPostRequest;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\MenPostResource;
use App\Http\Resources\CommentResource;
use App\Services\MenPostService;
use App\Services\CommentService;
use App\Services\ImageService;
use App\Models\MenPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Men post controller for review and warning system
 * 
 * Handles men post CRUD operations, flagging, comments,
 * and alert matching for safety and privacy features.
 * 
 * @package App\Http\Controllers\Api
 */
final class MenPostController extends Controller
{
    public function __construct(
        private readonly MenPostService $menPostService,
        private readonly CommentService $commentService,
        private readonly ImageService $imageService
    ) {}

    /**
     * Display a listing of men posts
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('per_page', 20);
            $filters = $request->only(['city', 'tags', 'name']);

            $posts = $this->menPostService->getMenPosts($page, $perPage, $filters);

            return response()->json([
                'posts' => MenPostResource::collection($posts),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve men posts',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created men post
     * 
     * @param StoreMenPostRequest $request
     * @return JsonResponse
     */
    public function store(StoreMenPostRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                if ($this->imageService->validateImage($image)) {
                    $data['photo_url'] = $this->imageService->uploadPostImage($image, 0, 'men');
                } else {
                    return response()->json([
                        'message' => 'Invalid image file',
                    ], 422);
                }
            }

            $post = $this->menPostService->createPost($request->user(), $data);

            return response()->json([
                'message' => 'Men post created successfully',
                'post' => new MenPostResource($post),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create men post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified men post
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $post = $this->menPostService->getPost($id);

            if (!$post) {
                return response()->json([
                    'message' => 'Men post not found',
                ], 404);
            }

            return response()->json([
                'post' => new MenPostResource($post),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve men post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Flag a men post
     * 
     * @param FlagMenPostRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function flag(FlagMenPostRequest $request, int $id): JsonResponse
    {
        try {
            $post = MenPost::find($id);

            if (!$post) {
                return response()->json([
                    'message' => 'Men post not found',
                ], 404);
            }

            $result = $this->menPostService->flag(
                $request->user(),
                $post,
                $request->validated()['flag_type']
            );

            return response()->json([
                'message' => 'Flag recorded successfully',
                'red_flags' => $result['red_flags'],
                'green_flags' => $result['green_flags'],
                'neutral_flags' => $result['neutral_flags'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to record flag',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get comments for a men post
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function comments(Request $request, int $id): JsonResponse
    {
        try {
            $post = MenPost::find($id);

            if (!$post) {
                return response()->json([
                    'message' => 'Men post not found',
                ], 404);
            }

            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('per_page', 20);

            $comments = $this->menPostService->getComments($post, $page, $perPage);

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
     * Add comment to a men post
     * 
     * @param StoreCommentRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function addComment(StoreCommentRequest $request, int $id): JsonResponse
    {
        try {
            $post = MenPost::find($id);

            if (!$post) {
                return response()->json([
                    'message' => 'Men post not found',
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
