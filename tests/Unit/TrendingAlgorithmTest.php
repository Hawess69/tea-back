<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\FeedPost;
use App\Services\FeedPostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Trending algorithm unit tests
 * 
 * Tests the trending algorithm implementation
 * including score calculation and ranking logic.
 * 
 * @package Tests\Unit
 */
final class TrendingAlgorithmTest extends TestCase
{
    use RefreshDatabase;

    private FeedPostService $feedPostService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->feedPostService = new FeedPostService();
    }

    /**
     * Test trending score calculation
     */
    public function test_can_calculate_trending_score(): void
    {
        $post = FeedPost::factory()->create([
            'upvotes' => 10,
            'downvotes' => 2,
            'comments_count' => 5,
            'created_at' => now()->subHours(2),
        ]);

        $score = $this->feedPostService->calculateTrendingScore($post);

        $this->assertIsFloat($score);
        $this->assertGreaterThan(0, $score);
    }

    /**
     * Test trending score with high engagement
     */
    public function test_trending_score_higher_with_high_engagement(): void
    {
        $highEngagementPost = FeedPost::factory()->create([
            'upvotes' => 100,
            'downvotes' => 10,
            'comments_count' => 50,
            'created_at' => now()->subHours(1),
        ]);

        $lowEngagementPost = FeedPost::factory()->create([
            'upvotes' => 5,
            'downvotes' => 1,
            'comments_count' => 2,
            'created_at' => now()->subHours(1),
        ]);

        $highScore = $this->feedPostService->calculateTrendingScore($highEngagementPost);
        $lowScore = $this->feedPostService->calculateTrendingScore($lowEngagementPost);

        $this->assertGreaterThan($lowScore, $highScore);
    }

    /**
     * Test trending score with time decay
     */
    public function test_trending_score_decreases_with_time(): void
    {
        $recentPost = FeedPost::factory()->create([
            'upvotes' => 50,
            'downvotes' => 5,
            'comments_count' => 20,
            'created_at' => now()->subHours(1),
        ]);

        $oldPost = FeedPost::factory()->create([
            'upvotes' => 50,
            'downvotes' => 5,
            'comments_count' => 20,
            'created_at' => now()->subDays(3),
        ]);

        $recentScore = $this->feedPostService->calculateTrendingScore($recentPost);
        $oldScore = $this->feedPostService->calculateTrendingScore($oldPost);

        $this->assertGreaterThan($oldScore, $recentScore);
    }

    /**
     * Test trending posts ranking
     */
    public function test_can_get_trending_posts_ranked(): void
    {
        // Create posts with different engagement levels
        $lowEngagementPost = FeedPost::factory()->create([
            'upvotes' => 5,
            'downvotes' => 1,
            'comments_count' => 2,
            'created_at' => now()->subHours(2),
        ]);

        $highEngagementPost = FeedPost::factory()->create([
            'upvotes' => 100,
            'downvotes' => 10,
            'comments_count' => 50,
            'created_at' => now()->subHours(1),
        ]);

        $mediumEngagementPost = FeedPost::factory()->create([
            'upvotes' => 25,
            'downvotes' => 3,
            'comments_count' => 10,
            'created_at' => now()->subHours(1),
        ]);

        $trendingPosts = $this->feedPostService->getTrendingPosts();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $trendingPosts);
        $this->assertCount(3, $trendingPosts);

        // The highest engagement post should be first
        $this->assertEquals($highEngagementPost->id, $trendingPosts->first()->id);
    }

    /**
     * Test trending score with zero engagement
     */
    public function test_trending_score_with_zero_engagement(): void
    {
        $post = FeedPost::factory()->create([
            'upvotes' => 0,
            'downvotes' => 0,
            'comments_count' => 0,
            'created_at' => now()->subHours(1),
        ]);

        $score = $this->feedPostService->calculateTrendingScore($post);

        $this->assertEquals(0, $score);
    }

    /**
     * Test trending score with negative votes
     */
    public function test_trending_score_with_negative_votes(): void
    {
        $post = FeedPost::factory()->create([
            'upvotes' => 5,
            'downvotes' => 10,
            'comments_count' => 3,
            'created_at' => now()->subHours(1),
        ]);

        $score = $this->feedPostService->calculateTrendingScore($post);

        $this->assertLessThan(0, $score);
    }
}


