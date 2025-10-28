<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\FeedPost;
use App\Models\MenPost;
use App\Models\Comment;
use App\Models\Vote;
use App\Models\Flag;
use Illuminate\Foundation\Testing\WithFaker;

class RealDataTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Use existing database data - no seeding needed
    }

    /** @test */
    public function test_real_users_exist_and_are_accessible()
    {
        $users = User::all();
        $this->assertGreaterThan(0, $users->count(), 'No users found in database');
        
        foreach ($users as $user) {
            $this->assertNotNull($user->name, "User {$user->id} has no name");
            $this->assertNotNull($user->email, "User {$user->id} has no email");
            $this->assertContains($user->role, ['user', 'admin'], "User {$user->id} has invalid role: {$user->role}");
        }
    }

    /** @test */
    public function test_real_feed_posts_exist_and_have_valid_data()
    {
        $feedPosts = FeedPost::with('user')->get();
        $this->assertGreaterThan(0, $feedPosts->count(), 'No feed posts found in database');
        
        foreach ($feedPosts as $post) {
            $this->assertNotNull($post->title, "Feed post {$post->id} has no title");
            $this->assertNotNull($post->content, "Feed post {$post->id} has no content");
            $this->assertNotNull($post->user, "Feed post {$post->id} has no associated user");
            $this->assertContains($post->status, ['published', 'draft', 'hidden'], "Feed post {$post->id} has invalid status: {$post->status}");
        }
    }

    /** @test */
    public function test_real_men_posts_exist_and_have_valid_data()
    {
        $menPosts = MenPost::with('user')->get();
        $this->assertGreaterThan(0, $menPosts->count(), 'No men posts found in database');
        
        foreach ($menPosts as $post) {
            $this->assertNotNull($post->full_name, "Men post {$post->id} has no full_name");
            $this->assertNotNull($post->city, "Men post {$post->id} has no city");
            $this->assertNotNull($post->caption, "Men post {$post->id} has no caption");
            $this->assertNotNull($post->user, "Men post {$post->id} has no associated user");
            $this->assertIsArray($post->tags, "Men post {$post->id} tags should be array");
        }
    }

    /** @test */
    public function test_real_comments_have_valid_polymorphic_relationships()
    {
        $comments = Comment::with(['user', 'commentable'])->get();
        $this->assertGreaterThan(0, $comments->count(), 'No comments found in database');
        
        foreach ($comments as $comment) {
            $this->assertNotNull($comment->body, "Comment {$comment->id} has no body");
            $this->assertNotNull($comment->user, "Comment {$comment->id} has no associated user");
            $this->assertNotNull($comment->commentable, "Comment {$comment->id} has no commentable relationship");
            $this->assertContains($comment->commentable_type, [FeedPost::class, MenPost::class], "Comment {$comment->id} has invalid commentable_type");
        }
    }

    /** @test */
    public function test_real_votes_have_valid_polymorphic_relationships()
    {
        $votes = Vote::with(['user', 'voteable'])->get();
        $this->assertGreaterThan(0, $votes->count(), 'No votes found in database');
        
        foreach ($votes as $vote) {
            $this->assertNotNull($vote->user, "Vote {$vote->id} has no associated user");
            $this->assertNotNull($vote->voteable, "Vote {$vote->id} has no voteable relationship");
            $this->assertContains($vote->vote_type, ['up', 'down'], "Vote {$vote->id} has invalid vote_type: {$vote->vote_type}");
            $this->assertContains($vote->voteable_type, [FeedPost::class, MenPost::class], "Vote {$vote->id} has invalid voteable_type");
        }
    }

    /** @test */
    public function test_real_flags_have_valid_polymorphic_relationships()
    {
        $flags = Flag::with(['user', 'flagable'])->get();
        $this->assertGreaterThan(0, $flags->count(), 'No flags found in database');
        
        foreach ($flags as $flag) {
            $this->assertNotNull($flag->user, "Flag {$flag->id} has no associated user");
            $this->assertNotNull($flag->flagable, "Flag {$flag->id} has no flagable relationship");
            $this->assertContains($flag->flag_type, ['red', 'green', 'neutral'], "Flag {$flag->id} has invalid flag_type: {$flag->flag_type}");
            $this->assertContains($flag->flagable_type, [FeedPost::class, MenPost::class], "Flag {$flag->id} has invalid flagable_type");
        }
    }

    /** @test */
    public function test_user_relationships_work_correctly()
    {
        $user = User::with(['feedPosts', 'menPosts', 'comments', 'votes', 'flags'])->first();
        $this->assertNotNull($user, 'No users found for relationship testing');
        
        // Test that relationships are properly loaded
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->feedPosts);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->menPosts);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->comments);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->votes);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->flags);
    }

    /** @test */
    public function test_feed_post_relationships_work_correctly()
    {
        $feedPost = FeedPost::with(['user', 'comments.user', 'votes.user'])->first();
        $this->assertNotNull($feedPost, 'No feed posts found for relationship testing');
        
        $this->assertInstanceOf(User::class, $feedPost->user);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $feedPost->comments);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $feedPost->votes);
    }

    /** @test */
    public function test_men_post_relationships_work_correctly()
    {
        $menPost = MenPost::with(['user', 'comments.user', 'flags.user'])->first();
        $this->assertNotNull($menPost, 'No men posts found for relationship testing');
        
        $this->assertInstanceOf(User::class, $menPost->user);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $menPost->comments);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $menPost->flags);
    }

    /** @test */
    public function test_model_accessors_and_mutators_work()
    {
        // Test MenPost tags mutator
        $menPost = MenPost::first();
        $this->assertIsArray($menPost->tags, 'MenPost tags should be array');
        
        // Test vote counts
        $feedPost = FeedPost::first();
        if ($feedPost) {
            $this->assertIsInt($feedPost->upvotes_count, 'FeedPost upvotes_count should be integer');
            $this->assertIsInt($feedPost->downvotes_count, 'FeedPost downvotes_count should be integer');
        }
        
        $menPost = MenPost::first();
        if ($menPost) {
            $this->assertIsInt($menPost->upvotes_count, 'MenPost upvotes_count should be integer');
            $this->assertIsInt($menPost->downvotes_count, 'MenPost downvotes_count should be integer');
        }
    }
}
