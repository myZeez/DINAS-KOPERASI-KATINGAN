<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first available user
        $userId = \App\Models\User::first()->id;

        \App\Models\Gallery::create([
            'title' => 'Rapat Koordinasi Koperasi',
            'description' => 'Rapat koordinasi bulanan dengan pengurus koperasi se-wilayah Jakarta Selatan untuk membahas program kerja tahun ini.',
            'image' => null, // Will use placeholder
            'category' => 'rapat',
            'tags' => 'rapat, koordinasi, koperasi',
            'views' => 125,
            'likes' => 23,
            'is_featured' => false,
            'status' => 'active',
            'user_id' => $userId
        ]);

        \App\Models\Gallery::create([
            'title' => 'Pelatihan UMKM Digital',
            'description' => 'Workshop pengembangan UMKM berbasis digital marketing untuk meningkatkan daya saing produk lokal.',
            'image' => null,
            'category' => 'kegiatan',
            'tags' => 'pelatihan, umkm, digital',
            'views' => 89,
            'likes' => 34,
            'is_featured' => true,
            'status' => 'active',
            'user_id' => $userId
        ]);

        \App\Models\Gallery::create([
            'title' => 'Acara Hari Koperasi',
            'description' => 'Peringatan Hari Koperasi Nasional dengan berbagai lomba dan pameran produk koperasi.',
            'image' => null,
            'category' => 'acara',
            'tags' => 'hari koperasi, lomba, peringatan',
            'views' => 201,
            'likes' => 67,
            'is_featured' => false,
            'status' => 'active',
            'user_id' => $userId
        ]);

        \App\Models\Gallery::create([
            'title' => 'Kantor Dinas Koperasi',
            'description' => 'Gedung kantor Dinas Koperasi dan UMKM Jakarta Selatan yang melayani masyarakat.',
            'image' => null,
            'category' => 'fasilitas',
            'tags' => 'kantor, gedung, fasilitas',
            'views' => 156,
            'likes' => 45,
            'is_featured' => false,
            'status' => 'active',
            'user_id' => $userId
        ]);
    }
}
