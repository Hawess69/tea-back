<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\FeedPost;
use App\Models\MenPost;
use Laravel\Sanctum\Sanctum;

class RealDataApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Use existing database data - no seeding needed
    }

    /** @test */
    public function test_auth_endpoints_with_real_users()
    {
        // Test login with real user
        $user = User::where('role', 'user')->first();
        $this->assertNotNull($user, 'No regular user found for testing');
        
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password' // Assuming seeded users have 'password' as password
        ]);
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role'
                    ],
                    'token'
                ]);
        
        $token = $response->json('token');
        $this->assertNotNull($token, 'No token returned from login');
    }

    /** @test */
    public function test_feed_posts_api_with_real_data()
    {
        $user = User::first();
        Sanctum::actingAs($user);
        
        // Test getting feed posts
        $response = $this->getJson('/api/v1/feed/posts');
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'content',
                            'status',
                            'user' => [
                                'id',
                                'name'
                            ],
                            'created_at'
                        ]
                    ],
                    'meta' => [
                        'current_page',
                        'total'
                    ]
                ]);
        
        $feedPosts = $response->json('data');
        $this->assertGreaterThan(0, count($feedPosts), 'No feed posts returned from API');
        
        // Test creating a new feed post
        $newPostData = [
            'title' => 'Test Feed Post from Real Data Test',
            'content' => 'This is a test feed post created during real data testing.',
            'status' => 'published'
        ];
        
        $response = $this->postJson('/api/v1/feed/posts', $newPostData);
        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'post' => [
                        'id',
                        'title',
                        'content',
                        'status',
                        'user' => [
                            'id',
                            'name'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function test_men_posts_api_with_real_data()
    {
        $user = User::first();
        Sanctum::actingAs($user);
        
        // Test getting men posts
        $response = $this->getJson('/api/v1/men/posts');
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'full_name',
                            'city',
                            'caption',
                            'tags',
                            'user' => [
                                'id',
                                'name'
                            ],
                            'created_at'
                        ]
                    ],
                    'meta' => [
                        'current_page',
                        'total'
                    ]
                ]);
        
        $menPosts = $response->json('data');
        $this->assertGreaterThan(0, count($menPosts), 'No men posts returned from API');
        
        // Test creating a new men post
        $newPostData = [
            'full_name' => 'Test User from Real Data Test',
            'city' => 'Test City',
            'caption' => 'This is a test men post created during real data testing.',
            'tags' => ['test', 'real-data']
        ];
        
        $response = $this->postJson('/api/v1/men/posts', $newPostData);
        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'post' => [
                        'id',
                        'full_name',
                        'city',
                        'caption',
                        'tags',
                        'user' => [
                            'id',
                            'name'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function test_voting_system_with_real_data()
    {
        $user = User::first();
        Sanctum::actingAs($user);
        
        $feedPost = FeedPost::first();
        $this->assertNotNull($feedPost, 'No feed posts found for voting test');
        
        // Test upvoting
        $response = $this->postJson("/api/v1/feed/posts/{$feedPost->id}/vote", [
            'vote_type' => 'up'
        ]);
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'upvotes',
                    'downvotes',
                    'user_vote'
                ]);
        
        // Test changing vote
        $response = $this->postJson("/api/v1/feed/posts/{$feedPost->id}/vote", [
            'vote_type' => 'down'
        ]);
        
        $response->assertStatus(200);
        
        // Test removing vote
        $response = $this->postJson("/api/v1/feed/posts/{$feedPost->id}/vote", [
            'vote_type' => 'down'
        ]);
        
        $response->assertStatus(200);
    }

    /** @test */
    public function test_commenting_system_with_real_data()
    {
        $user = User::first();
        Sanctum::actingAs($user);
        
        // Test commenting on feed post
        $feedPost = FeedPost::first();
        if ($feedPost) {
            $response = $this->postJson("/api/v1/feed/posts/{$feedPost->id}/comments", [
                'body' => 'This is a test comment on a feed post from real data test.'
            ]);
            
            $response->assertStatus(201)
                    ->assertJsonStructure([
                        'message',
                        'comment' => [
                            'id',
                            'body',
                            'user' => [
                                'id',
                                'name'
                            ],
                            'created_at'
                        ]
                    ]);
        }
        
        // Test commenting on men post
        $menPost = MenPost::first();
        if ($menPost) {
            $response = $this->postJson("/api/v1/men/posts/{$menPost->id}/comments", [
                'body' => 'This is a test comment on a men post from real data test.'
            ]);
            
            $response->assertStatus(201)
                    ->assertJsonStructure([
                        'message',
                        'comment' => [
                            'id',
                            'body',
                            'user' => [
                                'id',
                                'name'
                            ],
                            'created_at'
                        ]
                    ]);
        }
    }

    /** @test */
    public function test_flagging_system_with_real_data()
    {
        $user = User::first();
        Sanctum::actingAs($user);
        
        $menPost = MenPost::first();
        $this->assertNotNull($menPost, 'No men posts found for flagging test');
        
        // Test flagging
        $response = $this->postJson("/api/v1/men/posts/{$menPost->id}/flag", [
            'flag_type' => 'red',
            'reason' => 'Test flag from real data test'
        ]);
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'red_flags',
                    'green_flags',
                    'neutral_flags'
                ]);
        
        // Test changing flag
        $response = $this->postJson("/api/v1/men/posts/{$menPost->id}/flag", [
            'flag_type' => 'green',
            'reason' => 'Changed flag to green'
        ]);
        
        $response->assertStatus(200);
    }

    /** @test */
    public function test_admin_endpoints_with_real_admin_user()
    {
        $adminUser = User::where('role', 'admin')->first();
        $this->assertNotNull($adminUser, 'No admin user found for testing');
        
        Sanctum::actingAs($adminUser);
        
        // Test banning a user
        $regularUser = User::where('role', 'user')->first();
        if ($regularUser) {
            $response = $this->postJson("/api/v1/admin/users/{$regularUser->id}/ban", [
                'reason' => 'Test ban from real data test'
            ]);
            
            $response->assertStatus(200)
                    ->assertJsonStructure([
                        'message',
                        'user' => [
                            'id',
                            'status'
                        ]
                    ]);
        }
    }

    /** @test */
    public function test_profile_endpoint_with_real_user()
    {
        $user = User::first();
        Sanctum::actingAs($user);
        
        $response = $this->getJson('/api/v1/profile');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'status',
                        'created_at'
                    ]
                ]);
    }

    /** @test */
    public function test_events_and_alerts_endpoints_with_real_data()
    {
        $user = User::first();
        Sanctum::actingAs($user);
        
        // Test events endpoint
        $response = $this->getJson('/api/v1/events');
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'date',
                            'location'
                        ]
                    ]
                ]);
        
        // Test alerts endpoint
        $response = $this->getJson('/api/v1/alerts');
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'message',
                            'type',
                            'created_at'
                        ]
                    ]
                ]);
        
        // Test notifications endpoint
        $response = $this->getJson('/api/v1/notifications');
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'message',
                            'type',
                            'read_at',
                            'created_at'
                        ]
                    ]
                ]);
    }
}
