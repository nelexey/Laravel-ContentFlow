<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $this->call(RoleSeeder::class);

        $moderatorRole = Role::where('name', 'moderator')->first();
        $readerRole    = Role::where('name', 'reader')->first();

        $moderatorUser = User::factory()->create([
            'name'  => 'Moderator',
            'email' => 'moderator@example.com',
        ]);
        $moderatorUser->roles()->attach($moderatorRole);

        $readerUser = User::factory()->create([
            'name'  => 'Reader',
            'email' => 'reader@example.com',
        ]);
        $readerUser->roles()->attach($readerRole);

        $categories = \App\Models\Category::factory(5)->create();
        $articles   = \App\Models\Article::factory(20)
                         ->recycle($categories)
                         ->create();

        \App\Models\Comment::factory(25)
            ->recycle($articles)
            ->for($moderatorUser)
            ->create();

        \App\Models\Comment::factory(25)
            ->recycle($articles)
            ->for($readerUser)
            ->create();
    }
}
