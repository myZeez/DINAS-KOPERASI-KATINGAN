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
        $this->call([
            AdminUserSeeder::class,  // User harus dibuat dulu
            ProfileSeeder::class,
            HeroCarouselSeeder::class,
            FeaturedServiceSeeder::class,
            NewsSeeder::class,       // News seeder setelah user
            StructureSeeder::class,  // Structure seeder
            ReviewSeeder::class,     // Reviews sample data
        ]);
    }
}
