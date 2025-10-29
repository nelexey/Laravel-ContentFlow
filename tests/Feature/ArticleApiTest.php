<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create users
        $this->user = User::factory()->create();
        $this->admin = User::factory()->create();
        
        // Assign admin role
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin']);
        $this->admin->roles()->attach($adminRole);
    }

    public function test_public_can_list_articles()
    {
        // Create some articles
        Article::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/articles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'excerpt', 'author', 'created_at']
                ],
                'meta' => ['current_page', 'per_page', 'total', 'last_page']
            ]);
    }

    public function test_public_can_view_single_article()
    {
        $article = Article::factory()->create(['author_id' => $this->user->id]);

        $response = $this->getJson("/api/v1/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'title', 'content', 'author', 'created_at', 'updated_at'
            ]);
    }

    public function test_unauthenticated_user_cannot_create_article()
    {
        $response = $this->postJson('/api/v1/articles', [
            'title' => 'Test Article',
            'content' => 'This is a test article content.'
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_article()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/articles', [
                'title' => 'Test Article',
                'content' => 'This is a test article content.'
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'title', 'content', 'author', 'created_at', 'updated_at'
            ])
            ->assertJson([
                'title' => 'Test Article',
                'content' => 'This is a test article content.'
            ]);
    }

    public function test_authenticated_user_can_update_their_own_article()
    {
        $article = Article::factory()->create([
            'author_id' => $this->user->id,
            'title' => 'Original Title',
            'body' => 'Original content'
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/v1/articles/{$article->id}", [
                'title' => 'Updated Title',
                'content' => 'Updated content'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Title',
                'content' => 'Updated content'
            ]);
    }

    public function test_authenticated_user_cannot_update_someone_elses_article()
    {
        $otherUser = User::factory()->create();
        $article = Article::factory()->create([
            'author_id' => $otherUser->id,
            'title' => 'Original Title',
            'body' => 'Original content'
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/v1/articles/{$article->id}", [
                'title' => 'Updated Title',
                'content' => 'Updated content'
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_any_article()
    {
        $otherUser = User::factory()->create();
        $article = Article::factory()->create([
            'author_id' => $otherUser->id,
            'title' => 'Original Title',
            'body' => 'Original content'
        ]);

        $response = $this->actingAs($this->admin)
            ->putJson("/api/v1/articles/{$article->id}", [
                'title' => 'Updated Title',
                'content' => 'Updated content'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Title',
                'content' => 'Updated content'
            ]);
    }

    public function test_author_can_delete_their_own_article()
    {
        $article = Article::factory()->create(['author_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/articles/{$article->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    public function test_user_cannot_delete_someone_elses_article()
    {
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/articles/{$article->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('articles', ['id' => $article->id]);
    }

    public function test_admin_can_delete_any_article()
    {
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $otherUser->id]);

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/v1/articles/{$article->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}