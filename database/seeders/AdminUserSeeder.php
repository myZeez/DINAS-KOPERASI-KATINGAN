<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@dinaskoperasi.go.id'],
            [
                'name' => 'Admin Dinas Koperasi',
                'role' => 'super_admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'budi@dinaskoperasi.go.id'],
            [
                'name' => 'Budi Santoso',
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'siti@dinaskoperasi.go.id'],
            [
                'name' => 'Siti Rahayu',
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]
        );
    }
}
