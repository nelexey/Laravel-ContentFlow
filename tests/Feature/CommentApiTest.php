<?php

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $moderator;
    protected User $admin;
    protected Article $article;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create users
        $this->user = User::factory()->create();
        $this->moderator = User::factory()->create();
        $this->admin = User::factory()->create();
        
        // Assign roles
        $moderatorRole = \App\Models\Role::firstOrCreate(['name' => 'moderator']);
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin']);
        
        $this->moderator->roles()->attach($moderatorRole);
        $this->admin->roles()->attach($adminRole);
        
        // Create an article
        $this->article = Article::factory()->create(['author_id' => $this->user->id]);
    }

    public function test_authenticated_user_can_create_comment()
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/v1/articles/{$this->article->id}/comments", [
                'body' => 'This is a test comment.'
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'body', 'is_approved', 'author', 'created_at', 'updated_at'
            ])
            ->assertJson([
                'body' => 'This is a test comment.',
                'is_approved' => false
            ]);
    }

    public function test_unauthenticated_user_cannot_create_comment()
    {
        $response = $this->postJson("/api/v1/articles/{$this->article->id}/comments", [
            'body' => 'This is a test comment.'
        ]);

        $response->assertStatus(401);
    }

    public function test_moderator_can_approve_comment()
    {
        $comment = Comment::factory()->create([
            'article_id' => $this->article->id,
            'user_id' => $this->user->id,
            'is_approved' => false
        ]);

        $response = $this->actingAs($this->moderator)
            ->putJson("/api/v1/comments/{$comment->id}/approve");

        $response->assertStatus(200)
            ->assertJson([
                'is_approved' => true
            ]);
    }

    public function test_non_moderator_cannot_approve_comment()
    {
        $comment = Comment::factory()->create([
            'article_id' => $this->article->id,
            'user_id' => $this->user->id,
            'is_approved' => false
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/v1/comments/{$comment->id}/approve");

        $response->assertStatus(403);
    }

    public function test_comment_author_can_delete_their_comment()
    {
        $comment = Comment::factory()->create([
            'article_id' => $this->article->id,
            'user_id' => $this->user->id,
            'is_approved' => false
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/comments/{$comment->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_moderator_can_delete_any_comment()
    {
        $comment = Comment::factory()->create([
            'article_id' => $this->article->id,
            'user_id' => $this->user->id,
            'is_approved' => false
        ]);

        $response = $this->actingAs($this->moderator)
            ->deleteJson("/api/v1/comments/{$comment->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_admin_can_delete_any_comment()
    {
        $comment = Comment::factory()->create([
            'article_id' => $this->article->id,
            'user_id' => $this->user->id,
            'is_approved' => false
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/v1/comments/{$comment->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_user_cannot_delete_others_comment()
    {
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create([
            'article_id' => $this->article->id,
            'user_id' => $otherUser->id,
            'is_approved' => false
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/comments/{$comment->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }
}