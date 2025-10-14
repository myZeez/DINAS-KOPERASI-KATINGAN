<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super admin already exists
        $existingAdmin = User::where('email', 'admin@gmail.com')->first();

        if ($existingAdmin) {
            $this->command->info('Super Admin already exists with email: admin@gmail.com');
            return;
        }

        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Super Admin created successfully!');
        $this->command->info('Email: admin@gmail.com');
        $this->command->info('Password: admin123');
        $this->command->info('Role: super_admin');
    }
}
