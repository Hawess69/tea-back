<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Models\MenPost;
use App\Services\MenPostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Men post service unit tests
 * 
 * Tests men post service business logic
 * including CRUD operations, flagging, and alert matching.
 * 
 * @package Tests\Unit
 */
final class MenPostServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private MenPostService $menPostService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->menPostService = new MenPostService();
    }

    /**
     * Test get men posts
     */
    public function test_can_get_men_posts(): void
    {
        MenPost::factory()->count(5)->create();

        $posts = $this->menPostService->getMenPosts();

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $posts);
        $this->assertEquals(5, $posts->total());
    }

    /**
     * Test create men post
     */
    public function test_can_create_men_post(): void
    {
        $user = User::factory()->create();

        $postData = [
            'full_name' => $this->faker->name(),
            'city' => $this->faker->city(),
            'tags' => ['gym', 'instagram'],
            'caption' => $this->faker->paragraph(),
        ];

        $post = $this->menPostService->createPost($user, $postData);

        $this->assertInstanceOf(MenPost::class, $post);
        $this->assertEquals($postData['full_name'], $post->full_name);
        $this->assertEquals($postData['city'], $post->city);
        $this->assertEquals($postData['tags'], $post->tags);
        $this->assertEquals($postData['caption'], $post->caption);
        $this->assertEquals($user->id, $post->user_id);
    }

    /**
     * Test get single men post
     */
    public function test_can_get_single_men_post(): void
    {
        $post = MenPost::factory()->create();

        $retrievedPost = $this->menPostService->getPost($post->id);

        $this->assertInstanceOf(MenPost::class, $retrievedPost);
        $this->assertEquals($post->id, $retrievedPost->id);
    }

    /**
     * Test flag men post
     */
    public function test_can_flag_men_post(): void
    {
        $user = User::factory()->create();
        $post = MenPost::factory()->create();

        $result = $this->menPostService->flag($user, $post, 'red');

        $this->assertArrayHasKey('red_flags', $result);
        $this->assertArrayHasKey('green_flags', $result);
        $this->assertArrayHasKey('neutral_flags', $result);
        $this->assertEquals(1, $result['red_flags']);
        $this->assertEquals(0, $result['green_flags']);
        $this->assertEquals(0, $result['neutral_flags']);
    }

    /**
     * Test change flag on men post
     */
    public function test_can_change_flag_on_men_post(): void
    {
        $user = User::factory()->create();
        $post = MenPost::factory()->create();

        // First flag red
        $this->menPostService->flag($user, $post, 'red');
        
        // Change to green flag
        $result = $this->menPostService->flag($user, $post, 'green');

        $this->assertEquals(0, $result['red_flags']);
        $this->assertEquals(1, $result['green_flags']);
        $this->assertEquals(0, $result['neutral_flags']);
    }

    /**
     * Test get post comments
     */
    public function test_can_get_post_comments(): void
    {
        $post = MenPost::factory()->create();

        $comments = $this->menPostService->getComments($post);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $comments);
    }

    /**
     * Test add comment to post
     */
    public function test_can_add_comment_to_post(): void
    {
        $user = User::factory()->create();
        $post = MenPost::factory()->create();

        $comment = $this->menPostService->addComment($user, $post, 'Test comment');

        $this->assertEquals('Test comment', $comment->body);
        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);
        $this->assertEquals('men', $comment->post_type);
    }
}


