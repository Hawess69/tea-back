<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\FeedPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Feed post feature tests
 * 
 * Tests feed post CRUD operations, voting, comments,
 * and trending algorithm functionality.
 * 
 * @package Tests\Feature
 */
final class FeedPostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /**
     * Test get feed posts
     */
    public function test_can_get_feed_posts(): void
    {
        FeedPost::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/feed/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'posts',
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ]);
    }

    /**
     * Test create feed post
     */
    public function test_can_create_feed_post(): void
    {
        $postData = [
            'title' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/feed/posts', $postData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'post' => [
                    'id',
                    'title',
                    'body',
                    'upvotes',
                    'downvotes',
                ],
            ]);

        $this->assertDatabaseHas('feed_posts', [
            'title' => $postData['title'],
            'body' => $postData['body'],
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test create feed post with invalid data
     */
    public function test_create_feed_post_fails_with_invalid_data(): void
    {
        $postData = [
            'title' => 'A', // Too short
            'body' => 'Short', // Too short
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/feed/posts', $postData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'body']);
    }

    /**
     * Test get single feed post
     */
    public function test_can_get_single_feed_post(): void
    {
        $post = FeedPost::factory()->create();

        $response = $this->getJson("/api/v1/feed/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'post' => [
                    'id',
                    'title',
                    'body',
                    'upvotes',
                    'downvotes',
                ],
            ]);
    }

    /**
     * Test vote on feed post
     */
    public function test_can_vote_on_feed_post(): void
    {
        $post = FeedPost::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/v1/feed/posts/{$post->id}/vote", [
            'vote_type' => 'up',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'upvotes',
                'downvotes',
            ]);
    }

    /**
     * Test vote with invalid data
     */
    public function test_vote_fails_with_invalid_data(): void
    {
        $post = FeedPost::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/v1/feed/posts/{$post->id}/vote", [
            'vote_type' => 'invalid',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['vote_type']);
    }

    /**
     * Test get post comments
     */
    public function test_can_get_post_comments(): void
    {
        $post = FeedPost::factory()->create();

        $response = $this->getJson("/api/v1/feed/posts/{$post->id}/comments");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'comments',
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ]);
    }

    /**
     * Test add comment to post
     */
    public function test_can_add_comment_to_post(): void
    {
        $post = FeedPost::factory()->create();

        $commentData = [
            'body' => $this->faker->paragraph(),
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/v1/feed/posts/{$post->id}/comments", $commentData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'comment' => [
                    'id',
                    'body',
                    'user',
                ],
            ]);
    }

    /**
     * Test add comment with invalid data
     */
    public function test_add_comment_fails_with_invalid_data(): void
    {
        $post = FeedPost::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/v1/feed/posts/{$post->id}/comments", [
            'body' => '', // Empty body
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['body']);
    }

    /**
     * Test protected routes require authentication
     */
    public function test_protected_routes_require_authentication(): void
    {
        $response = $this->postJson('/api/v1/feed/posts', [
            'title' => 'Test',
            'body' => 'Test body',
        ]);

        $response->assertStatus(401);
    }
}


