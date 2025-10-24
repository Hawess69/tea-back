<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\FeedPost;
use App\Models\MenPost;
use App\Models\Comment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Stats overview widget for Filament admin panel
 * 
 * Displays key metrics and statistics for the platform
 * including user counts, post activity, and engagement metrics.
 * 
 * @package App\Filament\Widgets
 */
final class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            
            Stat::make('Active Users', User::where('status', 'active')->count())
                ->description('Active users')
                ->descriptionIcon('heroicon-m-user-check')
                ->color('success'),
            
            Stat::make('Feed Posts', FeedPost::count())
                ->description('Community posts')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info'),
            
            Stat::make('Men Posts', MenPost::count())
                ->description('Review posts')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),
            
            Stat::make('Comments', Comment::count())
                ->description('Total comments')
                ->descriptionIcon('heroicon-m-chat-bubble-left-ellipsis')
                ->color('info'),
            
            Stat::make('New This Week', User::where('created_at', '>=', now()->subWeek())->count())
                ->description('New users this week')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}


