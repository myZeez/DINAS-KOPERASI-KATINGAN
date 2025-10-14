<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeaturedService;

class FeaturedServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Pembinaan Koperasi',
                'description' => 'Pembinaan dan pengembangan koperasi untuk meningkatkan kesejahteraan anggota',
                'icon' => 'fas fa-handshake',
                'color' => '#00ff88',
                'link' => '#pembinaan',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'Pengembangan UMK',
                'description' => 'Pemberdayaan usaha mikro, kecil, dan menengah untuk pertumbuhan ekonomi',
                'icon' => 'fas fa-chart-line',
                'color' => '#00ff88',
                'link' => '#umk',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'title' => 'Pelatihan & Edukasi',
                'description' => 'Program pelatihan untuk meningkatkan kapasitas koperasi dan UKM',
                'icon' => 'fas fa-graduation-cap',
                'color' => '#00ff88',
                'link' => '#pelatihan',
                'sort_order' => 3,
                'is_active' => true
            ]
        ];

        foreach ($services as $service) {
            FeaturedService::create($service);
        }
    }
}
