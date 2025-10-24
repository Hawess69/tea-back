<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FeedPost;
use App\Models\MenPost;
use App\Models\Flag;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function index(): View
    {
        // Get basic statistics
        $totalUsers = User::count();
        $totalFeedPosts = FeedPost::count();
        $totalMenPosts = MenPost::count();
        $totalFlags = Flag::count();

        // Get recent activity from database
        $recentUsers = User::latest()->take(3)->get();
        $recentFeedPosts = FeedPost::with('user')->latest()->take(3)->get();
        $recentMenPosts = MenPost::with('user')->latest()->take(3)->get();
        $recentFlags = Flag::with('menPost')->latest()->take(3)->get();

        // Build recent activity array
        $recentActivity = collect();
        
        // Add recent users
        foreach ($recentUsers as $user) {
            $recentActivity->push([
                'type' => 'user',
                'description' => "New user registered: {$user->name}",
                'time' => $user->created_at->diffForHumans(),
                'user' => $user
            ]);
        }
        
        // Add recent feed posts
        foreach ($recentFeedPosts as $post) {
            $recentActivity->push([
                'type' => 'feed_post',
                'description' => "Feed post created: \"" . \Str::limit($post->title, 30) . "\"",
                'time' => $post->created_at->diffForHumans(),
                'post' => $post
            ]);
        }
        
        // Add recent men posts
        foreach ($recentMenPosts as $post) {
            $recentActivity->push([
                'type' => 'men_post',
                'description' => "Men post created: \"" . \Str::limit($post->caption, 30) . "\"",
                'time' => $post->created_at->diffForHumans(),
                'post' => $post
            ]);
        }
        
        // Add recent flags
        foreach ($recentFlags as $flag) {
            $recentActivity->push([
                'type' => 'flag',
                'description' => "Men post flagged for review",
                'time' => $flag->created_at->diffForHumans(),
                'flag' => $flag
            ]);
        }

        // Sort by creation time and take the most recent 10
        $recentActivity = $recentActivity->sortByDesc(function ($item) {
            return match($item['type']) {
                'user' => $item['user']->created_at,
                'feed_post' => $item['post']->created_at,
                'men_post' => $item['post']->created_at,
                'flag' => $item['flag']->created_at,
                default => now()
            };
        })->take(10);

        // Get chart data for last 7 days
        $chartData = $this->getChartData();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalFeedPosts', 
            'totalMenPosts',
            'totalFlags',
            'recentActivity',
            'chartData'
        ));
    }

    private function getChartData(): array
    {
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $last7Days->push([
                'date' => $date->format('M d'),
                'users' => User::whereDate('created_at', $date)->count(),
                'feed_posts' => FeedPost::whereDate('created_at', $date)->count(),
                'men_posts' => MenPost::whereDate('created_at', $date)->count(),
            ]);
        }

        return [
            'labels' => $last7Days->pluck('date')->toArray(),
            'users' => $last7Days->pluck('users')->toArray(),
            'feed_posts' => $last7Days->pluck('feed_posts')->toArray(),
            'men_posts' => $last7Days->pluck('men_posts')->toArray(),
        ];
    }
}
