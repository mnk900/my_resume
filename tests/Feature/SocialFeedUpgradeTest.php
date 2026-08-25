<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialFeedUpgradeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reshare_a_public_post()
    {
        $author = User::factory()->create();
        Portfolio::create(['user_id' => $author->id, 'title' => 'Author Portfolio', 'is_public' => true]);

        $resharer = User::factory()->create();
        Portfolio::create(['user_id' => $resharer->id, 'title' => 'Resharer Portfolio', 'is_public' => true]);

        $originalPost = Post::create([
            'user_id' => $author->id,
            'content' => 'Original public post content',
            'post_type' => 'general',
            'status' => 'published',
        ]);

        $response = $this->actingAs($resharer)->post(route('posts.reshare', $originalPost->id), [
            'content' => 'My thoughts on this post',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(1, $originalPost->fresh()->shares_count);

        $this->assertDatabaseHas('posts', [
            'user_id' => $resharer->id,
            'original_post_id' => $originalPost->id,
            'content' => 'My thoughts on this post',
            'post_type' => 'reshare',
        ]);

        $this->assertDatabaseHas('system_notifications', [
            'user_id' => $author->id,
            'sender_id' => $resharer->id,
            'type' => 'post_reshare',
        ]);
    }

    public function test_user_can_like_and_reply_to_comment()
    {
        $author = User::factory()->create();
        Portfolio::create(['user_id' => $author->id, 'title' => 'Author Portfolio']);

        $commenter = User::factory()->create();
        Portfolio::create(['user_id' => $commenter->id, 'title' => 'Commenter Portfolio']);

        $post = Post::create([
            'user_id' => $author->id,
            'content' => 'Feed post',
            'post_type' => 'general',
            'status' => 'published',
        ]);

        $comment = PostComment::create([
            'post_id' => $post->id,
            'user_id' => $commenter->id,
            'comment' => 'Great post!',
        ]);

        // Test liking comment
        $likeResponse = $this->actingAs($author)->post(route('comments.like', $comment->id));
        $likeResponse->assertRedirect();
        $this->assertEquals(1, $comment->fresh()->likes_count);
        $this->assertDatabaseHas('post_comment_likes', [
            'post_comment_id' => $comment->id,
            'user_id' => $author->id,
        ]);

        // Test nested reply to comment
        $replyResponse = $this->actingAs($author)->post(route('posts.comment', $post->id), [
            'parent_id' => $comment->id,
            'comment' => 'Thanks for your feedback!',
        ]);

        $replyResponse->assertRedirect();
        $this->assertDatabaseHas('post_comments', [
            'post_id' => $post->id,
            'user_id' => $author->id,
            'parent_id' => $comment->id,
            'comment' => 'Thanks for your feedback!',
        ]);

        $this->assertDatabaseHas('system_notifications', [
            'user_id' => $commenter->id,
            'sender_id' => $author->id,
            'type' => 'comment_reply',
        ]);
    }

    public function test_user_can_edit_post_and_track_history()
    {
        $user = User::factory()->create();
        Portfolio::create(['user_id' => $user->id, 'title' => 'User Portfolio']);

        $post = Post::create([
            'user_id' => $user->id,
            'content' => 'Initial post text',
            'post_type' => 'general',
            'status' => 'published',
        ]);

        $response = $this->actingAs($user)->put(route('posts.update', $post->id), [
            'content' => 'Updated post text version 2',
        ]);

        $response->assertRedirect();
        $this->assertEquals('Updated post text version 2', $post->fresh()->content);

        $this->assertDatabaseHas('post_histories', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'previous_content' => 'Initial post text',
        ]);

        $this->assertCount(1, $post->fresh()->histories);
    }

    public function test_user_can_share_job_opportunity_from_company_profile()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Portfolio::create(['user_id' => $user->id, 'title' => 'User Portfolio', 'is_public' => true]);

        $company = \App\Models\Company::create([
            'name' => 'Acme Corp',
            'slug' => 'acme-corp',
            'email' => 'contact@acme.test',
            'description' => 'A technology company',
        ]);
        $company->users()->attach($user->id, ['role' => 'owner']);

        $opportunity = \App\Models\Opportunity::create([
            'company_id' => $company->id,
            'posted_by_user_id' => $user->id,
            'type' => 'job',
            'title' => 'Lead Software Engineer',
            'description' => 'Building scalable systems',
            'status' => 'published',
        ]);

        $response = $this->actingAs($user)->post(route('posts.share-opportunity', $opportunity->id), [
            'content' => 'Check out our job opening!',
            'company_id' => $company->id,
        ]);

        $response->assertRedirect(route('feed.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'company_id' => $company->id,
            'opportunity_id' => $opportunity->id,
            'content' => 'Check out our job opening!',
            'post_type' => 'job_share',
            'status' => 'published',
        ]);
    }
}
