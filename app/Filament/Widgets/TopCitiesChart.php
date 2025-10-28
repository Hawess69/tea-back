<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\MenPost;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * Top cities chart widget for Filament admin panel
 * 
 * Displays the top cities where men posts are created
 * to understand geographic distribution of content.
 * 
 * @package App\Filament\Widgets
 */
final class TopCitiesChart extends ChartWidget
{
    protected static ?string $heading = 'Top Cities (Men Posts)';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $cities = MenPost::select('city', DB::raw('count(*) as count'))
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $labels = $cities->pluck('city')->toArray();
        $data = $cities->pluck('count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Posts by City',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                        'rgb(139, 92, 246)',
                        'rgb(236, 72, 153)',
                        'rgb(14, 165, 233)',
                        'rgb(34, 197, 94)',
                        'rgb(251, 146, 60)',
                        'rgb(220, 38, 127)',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}


