<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'adminuser',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('Password321!'), 
        ]);

        User::factory()->create([
            'username' => 'writerone',
            'email' => 'writerone@example.com',
            'role' => 'writer',
            'password' => bcrypt('Password321!'), 
        ]);

        User::factory()->create([
            'username' => 'writertwo',
            'email' => 'writertwo@example.com',
            'role' => 'writer',
            'password' => bcrypt('Password321!'), 
        ]);

        User::factory()->create([
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'role' => 'user',
            'password' => bcrypt('Password321!'), 
        ]);
    }
}
