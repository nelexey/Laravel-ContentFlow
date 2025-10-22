<?php

use App\Mail\NewCommentNotification;
use App\Models\Article;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('email is sent to moderators when comment is created', function () {
    // Create moderator role and users
    $moderatorRole = Role::create(['name' => 'moderator']);
    $moderator1 = User::factory()->create(['email' => 'moderator1@example.com']);
    $moderator2 = User::factory()->create(['email' => 'moderator2@example.com']);
    $moderator1->roles()->attach($moderatorRole);
    $moderator2->roles()->attach($moderatorRole);
    
    // Create reader role and user
    $readerRole = Role::create(['name' => 'reader']);
    $reader = User::factory()->create();
    $reader->roles()->attach($readerRole);
    
    // Create an article
    $article = Article::factory()->create();
    
    // Mock the mailer
    Mail::fake();
    
    // Act as reader and create comment
    $this->actingAs($reader);
    
    $response = $this->post(route('comments.store', $article), [
        'body' => 'This is a test comment that needs moderation.',
    ]);
    
    // Assert the comment was created
    $response->assertRedirect();
    $this->assertDatabaseHas('comments', [
        'article_id' => $article->id,
        'user_id' => $reader->id,
        'body' => 'This is a test comment that needs moderation.',
        'is_approved' => false,
    ]);
    
    // Assert email was sent to moderators
    Mail::assertSent(NewCommentNotification::class, 2);
});

test('email content is correct', function () {
    // Create reader user
    $reader = User::factory()->create(['name' => 'John Doe']);
    
    // Create an article
    $article = Article::factory()->create(['title' => 'Test Article']);
    
    // Create a comment
    $comment = $article->comments()->create([
        'body' => 'This is a test comment.',
        'user_id' => $reader->id,
    ]);
    
    // Create the mailable
    $mailable = new NewCommentNotification($comment);
    
    // Assert the mailable content
    $mailable->assertHasSubject('New Comment Requires Moderation');
    
    // Render the email content
    $content = $mailable->render();
    
    // Check that the content contains expected information
    expect($content)->toContain('Test Article');
    expect($content)->toContain('John Doe');
    expect($content)->toContain('This is a test comment.');
    expect($content)->toContain(route('admin.comments.index'));
});

test('no email is sent when there are no moderators', function () {
    // Create reader role and user
    $readerRole = Role::create(['name' => 'reader']);
    $reader = User::factory()->create();
    $reader->roles()->attach($readerRole);
    
    // Create an article
    $article = Article::factory()->create();
    
    // Mock the mailer
    Mail::fake();
    
    // Act as reader and create comment
    $this->actingAs($reader);
    
    $response = $this->post(route('comments.store', $article), [
        'body' => 'This is a test comment.',
    ]);
    
    // Assert the comment was created
    $response->assertRedirect();
    $this->assertDatabaseHas('comments', [
        'article_id' => $article->id,
        'user_id' => $reader->id,
        'body' => 'This is a test comment.',
        'is_approved' => false,
    ]);
    
    // Assert no email was sent since there are no moderators
    Mail::assertNothingSent();
});