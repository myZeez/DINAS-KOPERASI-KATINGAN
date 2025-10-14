<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        Review::insert([
            [
                'name' => 'Sarah Putri',
                'email' => 'sarah@example.com',
                'rating' => 5,
                'comment' => 'Pelayanan sangat memuaskan!',
                'is_visible' => true,
                'is_verified' => true,
                'status' => 'approved',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'rating' => 4,
                'comment' => 'Cukup baik, bisa ditingkatkan.',
                'is_visible' => true,
                'is_verified' => false,
                'status' => 'approved',
                'created_at' => $now->copy()->subDay(),
                'updated_at' => $now->copy()->subDay(),
            ],
            [
                'name' => null,
                'email' => null,
                'rating' => 3,
                'comment' => 'Biasa saja.',
                'is_visible' => false,
                'is_verified' => false,
                'status' => 'pending',
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ],
        ]);
    }
}
