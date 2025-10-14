<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        Profile::create([
            'name' => 'Dinas Koperasi dan UKM',
            'address' => 'Jl. Gatot Subroto Kav. 94, Jakarta Selatan 12870',
            'phone' => '(021) 5254578',
            'email' => 'info@dinaskoperasi.go.id',
            'vision' => 'Menjadi lembaga terdepan dalam pemberdayaan koperasi dan UMKM yang berdaya saing tinggi dan berkelanjutan.',
            'mission' => "1. Meningkatkan kualitas dan kapasitas koperasi serta UMKM\n2. Mengembangkan akses permodalan dan pemasaran\n3. Memperkuat kelembagaan koperasi dan UMKM\n4. Meningkatkan inovasi dan teknologi",
            'latitude' => -6.2297,
            'longitude' => 106.8241,
            'operating_hours' => [
                'senin_jumat' => '08:00 - 16:00',
                'sabtu' => '08:00 - 12:00',
                'minggu' => 'Tutup'
            ]
        ]);
    }
}
