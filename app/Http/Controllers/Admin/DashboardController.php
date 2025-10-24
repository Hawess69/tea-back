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

        // Get recent activity (simplified for now)
        $recentActivity = [
            [
                'description' => 'New user registered: John Doe',
                'time' => '2 hours ago'
            ],
            [
                'description' => 'Feed post created: "Great day at the beach!"',
                'time' => '4 hours ago'
            ],
            [
                'description' => 'Men post flagged for review',
                'time' => '6 hours ago'
            ],
            [
                'description' => 'User banned: spammer123',
                'time' => '1 day ago'
            ],
            [
                'description' => 'System maintenance completed',
                'time' => '2 days ago'
            ]
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalFeedPosts', 
            'totalMenPosts',
            'totalFlags',
            'recentActivity'
        ));
    }
}
