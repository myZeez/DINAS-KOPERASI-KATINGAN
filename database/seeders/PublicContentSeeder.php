<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PublicContent;

class PublicContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hero Section Content
        PublicContent::create([
            'section_name' => 'hero_main',
            'title' => 'Pemberdayaan Koperasi dan UKM',
            'content' => 'Mendukung pertumbuhan ekonomi masyarakat melalui pemberdayaan koperasi dan usaha mikro kecil menengah',
            'settings' => [
                'subtitle' => 'Dinas Koperasi dan UKM Jakarta Selatan',
                'button_text' => 'Selengkapnya',
                'button_link' => '/about'
            ],
            'is_active' => true
        ]);

        PublicContent::create([
            'section_name' => 'hero_secondary',
            'title' => 'Pelayanan Terpadu Koperasi',
            'content' => 'Memberikan pelayanan terbaik dalam pengurusan perizinan dan pembinaan koperasi',
            'settings' => [
                'subtitle' => 'Layanan Koperasi',
                'button_text' => 'Layanan',
                'button_link' => '/services'
            ],
            'is_active' => true
        ]);

        PublicContent::create([
            'section_name' => 'hero_tertiary',
            'title' => 'Inovasi dan Teknologi UKM',
            'content' => 'Mendorong adopsi teknologi dan inovasi dalam usaha mikro kecil menengah',
            'settings' => [
                'subtitle' => 'Program Inovasi',
                'button_text' => 'Program',
                'button_link' => '/programs'
            ],
            'is_active' => true
        ]);

        // About Section
        PublicContent::create([
            'section_name' => 'about_intro',
            'title' => 'Tentang Dinas Koperasi dan UKM',
            'content' => 'Dinas Koperasi dan UKM Jakarta Selatan berkomitmen untuk meningkatkan kesejahteraan masyarakat melalui pengembangan ekonomi kerakyatan berbasis koperasi dan usaha mikro kecil menengah.',
            'settings' => [
                'highlight' => 'Membangun ekonomi yang inklusif dan berkelanjutan'
            ],
            'is_active' => true
        ]);

        // Services Overview
        PublicContent::create([
            'section_name' => 'services_intro',
            'title' => 'Layanan Kami',
            'content' => 'Kami menyediakan berbagai layanan untuk mendukung pengembangan koperasi dan UKM di wilayah Jakarta Selatan.',
            'settings' => [
                'services_count' => 10,
                'active_cooperatives' => 150
            ],
            'is_active' => true
        ]);

        // Contact Information
        PublicContent::create([
            'section_name' => 'contact_info',
            'title' => 'Hubungi Kami',
            'content' => 'Jl. Prapatan Raya No. 10, Kebayoran Baru, Jakarta Selatan 12160',
            'settings' => [
                'phone' => '021-7256789',
                'email' => 'info@dinaskoperasi.jakarta.go.id',
                'office_hours' => 'Senin - Jumat: 08:00 - 16:00 WIB'
            ],
            'is_active' => true
        ]);
    }
}
