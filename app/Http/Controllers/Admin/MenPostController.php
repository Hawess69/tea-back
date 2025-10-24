<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateMenPostRequest;
use App\Models\MenPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class MenPostController extends Controller
{
    public function __construct()
    {
        // Admin middleware is already applied in routes
    }

    /**
     * Display a listing of men posts
     */
    public function index(Request $request): View
    {
        $query = MenPost::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('caption', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->get('city'));
        }

        $posts = $query->latest()->paginate(15);

        return view('admin.posts.men', compact('posts'));
    }

    /**
     * Display the specified men post
     */
    public function show(MenPost $menPost): View
    {
        $menPost->load('user', 'comments.user', 'flags');
        return view('admin.posts.show-men', compact('menPost'));
    }

    /**
     * Show the form for editing the specified men post
     */
    public function edit(MenPost $menPost): View
    {
        return view('admin.posts.edit-men', compact('menPost'));
    }

    /**
     * Update the specified men post
     */
    public function update(UpdateMenPostRequest $request, MenPost $menPost): RedirectResponse
    {
        $menPost->update($request->validated());

        return redirect()->route('admin.posts.men.index')
            ->with('success', 'Men post updated successfully.');
    }

    /**
     * Remove the specified men post
     */
    public function destroy(MenPost $menPost): RedirectResponse
    {
        $menPost->delete();

        return redirect()->route('admin.posts.men.index')
            ->with('success', 'Men post deleted successfully.');
    }

    /**
     * Hide or unhide a men post (API endpoint)
     */
    public function hide(Request $request, MenPost $menPost): JsonResponse
    {
        $menPost->update([
            'status' => $menPost->status === 'hidden' ? 'published' : 'hidden'
        ]);

        return response()->json([
            'success' => true,
            'message' => $menPost->status === 'hidden' ? 'Post hidden successfully.' : 'Post unhidden successfully.',
            'status' => $menPost->status
        ]);
    }

    /**
     * Publish a draft men post (API endpoint)
     */
    public function publish(Request $request, MenPost $menPost): JsonResponse
    {
        $menPost->update(['status' => 'published']);

        return response()->json([
            'success' => true,
            'message' => 'Post published successfully.',
            'status' => $menPost->status
        ]);
    }
}
