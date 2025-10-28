<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\MenPost;
use Filament\Widgets\ChartWidget;

/**
 * Flag ratio chart widget for Filament admin panel
 * 
 * Displays the ratio of red, green, and neutral flags
 * for men posts to understand community sentiment.
 * 
 * @package App\Filament\Widgets
 */
final class FlagRatioChart extends ChartWidget
{
    protected static ?string $heading = 'Men Post Flag Distribution';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $redFlags = 0;
        $greenFlags = 0;
        $neutralFlags = 0;

        MenPost::chunk(100, function ($posts) use (&$redFlags, &$greenFlags, &$neutralFlags) {
            foreach ($posts as $post) {
                $flagCounts = $post->flag_counts ?? [];
                $redFlags += $flagCounts['red'] ?? 0;
                $greenFlags += $flagCounts['green'] ?? 0;
                $neutralFlags += $flagCounts['neutral'] ?? 0;
            }
        });

        return [
            'datasets' => [
                [
                    'data' => [$redFlags, $greenFlags, $neutralFlags],
                    'backgroundColor' => [
                        'rgb(239, 68, 68)', // Red
                        'rgb(34, 197, 94)', // Green
                        'rgb(156, 163, 175)', // Neutral
                    ],
                ],
            ],
            'labels' => ['Red Flags', 'Green Flags', 'Neutral Flags'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}


