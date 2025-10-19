<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


        $categories = \App\Models\Category::factory(5)->create();


        $articles = \App\Models\Article::factory(20)->recycle($categories)->create();

        // 4.  50 комментов, привязывая их к случайным статьям от Test user
        \App\Models\Comment::factory(50)
            ->recycle($articles) // Берет случайные ID из $articles
            ->for($user)         // Привязывает все комменты к $user
            ->create();
    }
}
