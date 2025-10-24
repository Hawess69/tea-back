<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateFeedPostRequest;
use App\Models\FeedPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class FeedPostController extends Controller
{
    public function __construct()
    {
        // Admin middleware is already applied in routes
    }

    /**
     * Display a listing of feed posts
     */
    public function index(Request $request): View
    {
        $query = FeedPost::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by type
        if ($request->filled('type')) {
            if ($request->get('type') === 'with_image') {
                $query->whereNotNull('image_url');
            } elseif ($request->get('type') === 'text_only') {
                $query->whereNull('image_url');
            }
        }

        $posts = $query->latest()->paginate(15);

        return view('admin.posts.feed', compact('posts'));
    }

    /**
     * Display the specified feed post
     */
    public function show(FeedPost $feedPost): View
    {
        $feedPost->load('user', 'comments.user', 'votes.user');
        return view('admin.posts.show-feed', compact('feedPost'));
    }

    /**
     * Show the form for editing the specified feed post
     */
    public function edit(FeedPost $feedPost): View
    {
        return view('admin.posts.edit-feed', compact('feedPost'));
    }

    /**
     * Update the specified feed post
     */
    public function update(UpdateFeedPostRequest $request, FeedPost $feedPost): RedirectResponse
    {
        $feedPost->update($request->validated());

        return redirect()->route('admin.posts.feed.index')
            ->with('success', 'Feed post updated successfully.');
    }

    /**
     * Remove the specified feed post
     */
    public function destroy(FeedPost $feedPost): RedirectResponse
    {
        $feedPost->delete();

        return redirect()->route('admin.posts.feed.index')
            ->with('success', 'Feed post deleted successfully.');
    }

    /**
     * Hide or unhide a feed post (API endpoint)
     */
    public function hide(Request $request, FeedPost $feedPost): JsonResponse
    {
        $feedPost->update([
            'status' => $feedPost->status === 'hidden' ? 'published' : 'hidden'
        ]);

        return response()->json([
            'success' => true,
            'message' => $feedPost->status === 'hidden' ? 'Post hidden successfully.' : 'Post unhidden successfully.',
            'status' => $feedPost->status
        ]);
    }

    /**
     * Publish a draft feed post (API endpoint)
     */
    public function publish(Request $request, FeedPost $feedPost): JsonResponse
    {
        $feedPost->update(['status' => 'published']);

        return response()->json([
            'success' => true,
            'message' => 'Post published successfully.',
            'status' => $feedPost->status
        ]);
    }
}
