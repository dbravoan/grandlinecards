<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create permissions (optional, but good practice)
        // Permission::create(['name' => 'manage cards', 'guard_name' => 'admin']);
        
        // 2. Create Roles for Admin Guard
        $role = Role::firstOrCreate(['name' => 'dbadmin', 'guard_name' => 'admin']);

        // 3. Create Default Admin User
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@grandline.com'],
            [
                'name' => 'Sengoku',
                'password' => Hash::make('password'),
            ]
        );

        // 4. Assign Role
        $admin->assignRole($role);
        
        $this->command->info('Admin user created: admin@grandline.com / password');
        $this->command->info('Role assigned: dbadmin');
    }
}
