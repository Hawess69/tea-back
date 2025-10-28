<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Models\FeedPost;
use App\Services\FeedPostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Feed post service unit tests
 * 
 * Tests feed post service business logic
 * including CRUD operations, voting, and trending algorithm.
 * 
 * @package Tests\Unit
 */
final class FeedPostServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private FeedPostService $feedPostService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->feedPostService = new FeedPostService();
    }

    /**
     * Test get feed posts
     */
    public function test_can_get_feed_posts(): void
    {
        FeedPost::factory()->count(5)->create();

        $posts = $this->feedPostService->getFeedPosts();

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $posts);
        $this->assertEquals(5, $posts->total());
    }

    /**
     * Test create feed post
     */
    public function test_can_create_feed_post(): void
    {
        $user = User::factory()->create();

        $postData = [
            'title' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
        ];

        $post = $this->feedPostService->createPost($user, $postData);

        $this->assertInstanceOf(FeedPost::class, $post);
        $this->assertEquals($postData['title'], $post->title);
        $this->assertEquals($postData['body'], $post->body);
        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals(0, $post->upvotes);
        $this->assertEquals(0, $post->downvotes);
        $this->assertEquals(0, $post->comments_count);
    }

    /**
     * Test get single feed post
     */
    public function test_can_get_single_feed_post(): void
    {
        $post = FeedPost::factory()->create();

        $retrievedPost = $this->feedPostService->getPost($post->id);

        $this->assertInstanceOf(FeedPost::class, $retrievedPost);
        $this->assertEquals($post->id, $retrievedPost->id);
    }

    /**
     * Test vote on feed post
     */
    public function test_can_vote_on_feed_post(): void
    {
        $user = User::factory()->create();
        $post = FeedPost::factory()->create();

        $result = $this->feedPostService->vote($user, $post, 'up');

        $this->assertArrayHasKey('upvotes', $result);
        $this->assertArrayHasKey('downvotes', $result);
        $this->assertEquals(1, $result['upvotes']);
        $this->assertEquals(0, $result['downvotes']);
    }

    /**
     * Test change vote on feed post
     */
    public function test_can_change_vote_on_feed_post(): void
    {
        $user = User::factory()->create();
        $post = FeedPost::factory()->create();

        // First vote up
        $this->feedPostService->vote($user, $post, 'up');
        
        // Change to down vote
        $result = $this->feedPostService->vote($user, $post, 'down');

        $this->assertEquals(0, $result['upvotes']);
        $this->assertEquals(1, $result['downvotes']);
    }

    /**
     * Test get post comments
     */
    public function test_can_get_post_comments(): void
    {
        $post = FeedPost::factory()->create();

        $comments = $this->feedPostService->getComments($post);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $comments);
    }

    /**
     * Test add comment to post
     */
    public function test_can_add_comment_to_post(): void
    {
        $user = User::factory()->create();
        $post = FeedPost::factory()->create();

        $comment = $this->feedPostService->addComment($user, $post, 'Test comment');

        $this->assertEquals('Test comment', $comment->body);
        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);
        $this->assertEquals('feed', $comment->post_type);
    }

    /**
     * Test get trending posts
     */
    public function test_can_get_trending_posts(): void
    {
        FeedPost::factory()->count(5)->create();

        $trendingPosts = $this->feedPostService->getTrendingPosts();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $trendingPosts);
    }
}


