<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Standard Fixed User
        User::firstOrCreate(
            ['email' => 'capitan@grandline.com'],
            [
                'name' => 'Monkey D. Luffy',
                'password' => Hash::make('password'),
                'email_verified_at' => now(), // Auto-verify for easy testing
            ]
        );

        // 2. Create Random Users
        User::factory(10)->create();
        
        $this->command->info('Users seeded: capitan@grandline.com / password');
    }
}
