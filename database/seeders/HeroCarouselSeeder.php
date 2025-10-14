<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroCarousel;

class HeroCarouselSeeder extends Seeder
{
    public function run(): void
    {
        $carousels = [
            [
                'title' => 'Pemberdayaan Koperasi dan UKM',
                'subtitle' => 'Mendukung pertumbuhan ekonomi masyarakat melalui pemberdayaan koperasi dan usaha mikro kecil menengah',
                'image' => 'carousel/hero-1.jpg',
                'button_text' => 'Pelajari Lebih Lanjut',
                'button_link' => '#layanan',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'Pelayanan Terpadu Koperasi',
                'subtitle' => 'Memberikan pelayanan terbaik dalam pengurusan perizinan dan pembinaan koperasi',
                'image' => 'carousel/hero-2.jpg',
                'button_text' => 'Hubungi Kami',
                'button_link' => '#kontak',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'title' => 'Inovasi dan Teknologi UKM',
                'subtitle' => 'Mendorong adopsi teknologi dan inovasi dalam usaha mikro kecil menengah',
                'image' => 'carousel/hero-3.jpg',
                'button_text' => 'Lihat Program',
                'button_link' => '#program',
                'sort_order' => 3,
                'is_active' => true
            ]
        ];

        foreach ($carousels as $carousel) {
            HeroCarousel::create($carousel);
        }
    }
}
