<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'moderator']);
        Role::firstOrCreate(['name' => 'reader']); 
        Role::firstOrCreate(['name' => 'admin']); 
    }
}