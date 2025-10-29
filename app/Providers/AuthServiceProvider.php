<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Article::class => ArticlePolicy::class,
    ];
    
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Article policy is automatically registered above
        
        // Comment gates
        Gate::define('create-comment', function (User $user) {
            // Any authenticated user can create comments
            return true;
        });

        Gate::define('update-comment', function (User $user, Comment $comment) {
            // Only the comment author can update their comment
            return $user->id === $comment->user_id;
        });

        Gate::define('delete-comment', function (User $user, Comment $comment) {
            // Comment author, moderator, or admin can delete
            return $user->id === $comment->user_id || 
                   $user->hasRole('moderator') || 
                   $user->hasRole('admin');
        });
        
        Gate::define('approve-comment', function (User $user) {
            // Only moderators or admins can approve comments
            return $user->hasRole('moderator') || $user->hasRole('admin');
        });
    }
}