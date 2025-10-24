<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\MenPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Men post feature tests
 * 
 * Tests men post CRUD operations, flagging, comments,
 * and alert matching functionality.
 * 
 * @package Tests\Feature
 */
final class MenPostTest extends TestCase
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
     * Test get men posts
     */
    public function test_can_get_men_posts(): void
    {
        MenPost::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/men/posts');

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
     * Test create men post
     */
    public function test_can_create_men_post(): void
    {
        $postData = [
            'full_name' => $this->faker->name(),
            'city' => $this->faker->city(),
            'tags' => ['gym', 'instagram'],
            'caption' => $this->faker->paragraph(),
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/men/posts', $postData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'post' => [
                    'id',
                    'full_name',
                    'city',
                    'tags',
                    'caption',
                ],
            ]);

        $this->assertDatabaseHas('men_posts', [
            'full_name' => $postData['full_name'],
            'city' => $postData['city'],
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test create men post with invalid data
     */
    public function test_create_men_post_fails_with_invalid_data(): void
    {
        $postData = [
            'full_name' => 'A', // Too short
            'city' => 'B', // Too short
            'caption' => 'Short', // Too short
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/men/posts', $postData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['full_name', 'city', 'caption']);
    }

    /**
     * Test get single men post
     */
    public function test_can_get_single_men_post(): void
    {
        $post = MenPost::factory()->create();

        $response = $this->getJson("/api/v1/men/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'post' => [
                    'id',
                    'full_name',
                    'city',
                    'tags',
                    'caption',
                ],
            ]);
    }

    /**
     * Test flag men post
     */
    public function test_can_flag_men_post(): void
    {
        $post = MenPost::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/v1/men/posts/{$post->id}/flag", [
            'flag_type' => 'red',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'red_flags',
                'green_flags',
                'neutral_flags',
            ]);
    }

    /**
     * Test flag with invalid data
     */
    public function test_flag_fails_with_invalid_data(): void
    {
        $post = MenPost::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/v1/men/posts/{$post->id}/flag", [
            'flag_type' => 'invalid',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['flag_type']);
    }

    /**
     * Test get post comments
     */
    public function test_can_get_post_comments(): void
    {
        $post = MenPost::factory()->create();

        $response = $this->getJson("/api/v1/men/posts/{$post->id}/comments");

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
        $post = MenPost::factory()->create();

        $commentData = [
            'body' => $this->faker->paragraph(),
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/v1/men/posts/{$post->id}/comments", $commentData);

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
     * Test protected routes require authentication
     */
    public function test_protected_routes_require_authentication(): void
    {
        $response = $this->postJson('/api/v1/men/posts', [
            'full_name' => 'Test Name',
            'city' => 'Test City',
            'caption' => 'Test caption',
        ]);

        $response->assertStatus(401);
    }
}


