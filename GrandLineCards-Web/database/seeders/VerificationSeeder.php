<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\Card;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VerificationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('--- Starting System Verification ---');

        // Check Connection
        try {
            DB::connection()->getPdo();
            $this->command->info('✅ Database connection successful.');
        } catch (\Exception $e) {
            $this->command->error('❌ Database connection failed: ' . $e->getMessage());
            return;
        }

        // Verify Users
        $userCount = User::count();
        $this->command->info("📊 Current Users: {$userCount}");
        if ($userCount === 0) {
            $this->command->warn('⚠️ No users found. Creating a test user...');
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
            $this->command->info('✅ Test user created (test@example.com / password).');
        }

        // Verify Admins
        if (Schema::hasTable('admins')) {
            $adminCount = Admin::count();
            $this->command->info("📊 Current Admins: {$adminCount}");
            if ($adminCount === 0) {
                $this->command->warn('⚠️ No admins found. Running AdminSeeder...');
                $this->call(AdminSeeder::class);
                $this->command->info('✅ AdminSeeder executed.');
            }
        } else {
            $this->command->error('❌ Admins table does not exist!');
        }

        // Verify Cards (Core Data)
        if (Schema::hasTable('cards')) {
            $cardCount = Card::count();
            $this->command->info("📊 Current Cards: {$cardCount}");
            if ($cardCount === 0) {
                $this->command->warn('⚠️ No cards found. You might want to run CardSeeder or import data.');
            } else {
                 $firstCard = Card::first();
                 $this->command->info("✅ Cards table readable. First card ID: {$firstCard->id}");
            }
        } else {
             $this->command->error('❌ Cards table does not exist!');
        }

        $this->command->info('--- Verification Complete ---');
    }
}
