<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('moderator')) {
            return true;
        }
        return null;
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Article $article): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('moderator');
    }

    public function update(User $user, Article $article): bool
    {
        return $user->hasRole('moderator');
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->hasRole('moderator');
    }

    public function restore(User $user, Article $article): bool
    {
        return false;
    }

    public function forceDelete(User $user, Article $article): bool
    {
        return false;
    }
}
