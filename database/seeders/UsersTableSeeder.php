<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@dinaskoperasi.go.id',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'is_active' => true,
            'last_login_at' => now(),
        ]);

        // Create Admin
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@dinaskoperasi.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'last_login_at' => now()->subHour(),
        ]);

        // Create Editor
        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@dinaskoperasi.go.id',
            'password' => Hash::make('password123'),
            'role' => 'editor',
            'is_active' => false,
            'last_login_at' => now()->subDays(2),
        ]);

        // Create more users
        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@dinaskoperasi.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'last_login_at' => now()->subMinutes(30),
        ]);

        User::create([
            'name' => 'Dewi Kartika',
            'email' => 'dewi@dinaskoperasi.go.id',
            'password' => Hash::make('password123'),
            'role' => 'editor',
            'is_active' => true,
            'last_login_at' => now()->subHours(3),
        ]);
    }
}
