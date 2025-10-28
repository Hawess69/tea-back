<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\FeedPostService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Recalculate trending job for feed posts
 * 
 * Handles background recalculation of trending scores
 * for feed posts based on votes and time decay.
 * 
 * @package App\Jobs
 */
final class RecalculateTrendingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     * 
     * @param FeedPostService $feedPostService
     * @return void
     */
    public function handle(FeedPostService $feedPostService): void
    {
        try {
            Log::info('Starting trending recalculation');

            // Clear existing trending cache
            Cache::forget('trending_posts');

            // Recalculate trending posts
            $trendingPosts = $feedPostService->getTrendingPosts(50);

            // Cache the results
            Cache::put('trending_posts', $trendingPosts, 3600); // 1 hour

            Log::info('Trending recalculation completed', [
                'posts_count' => $trendingPosts->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Trending recalculation failed', [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     * 
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('RecalculateTrendingJob failed', [
            'error' => $exception->getMessage(),
        ]);
    }
}


