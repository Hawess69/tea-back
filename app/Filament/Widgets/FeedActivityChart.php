<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\FeedPost;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

/**
 * Feed activity chart widget for Filament admin panel
 * 
 * Displays feed post activity over time with daily post counts
 * and engagement metrics for content analysis.
 * 
 * @package App\Filament\Widgets
 */
final class FeedActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Feed Activity (Last 30 Days)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M j');
            
            $data[] = FeedPost::whereDate('created_at', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Posts Created',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}


