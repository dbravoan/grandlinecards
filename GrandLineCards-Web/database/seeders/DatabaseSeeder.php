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
        // User::factory(10)->create();

        $this->call([
            CardSeeder::class,
        ]);
        
        // Create Admin User & Role
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
        ]);

        // Create Test User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@grandlinecards.es',
            'password' => bcrypt('password'),
        ]);

        // User::factory(10)->create();

    }
}
