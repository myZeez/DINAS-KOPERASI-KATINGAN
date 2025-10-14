<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get available user IDs dynamically
        $userIds = \App\Models\User::pluck('id')->toArray();

        if (empty($userIds)) {
            throw new \Exception('No users found. Please run AdminUserSeeder first.');
        }

        $newsData = [
            [
                'title' => 'Pelatihan Kewirausahaan UMKM Sukses Digelar',
                'content' => 'Dinas Koperasi berhasil menggelar pelatihan kewirausahaan untuk pelaku UMKM se-wilayah. Pelatihan ini diikuti oleh 100 peserta dari berbagai sektor usaha. Materi yang disampaikan meliputi strategi pemasaran digital, manajemen keuangan, dan pengembangan produk inovatif.',
                'image' => 'news/sample-training.jpg',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'user_id' => $userIds[0] ?? $userIds[0]
            ],
            [
                'title' => 'Program Bantuan Modal Koperasi Tahap 2 Dimulai',
                'content' => 'Dinas Koperasi meluncurkan program bantuan modal tahap 2 untuk mendukung pengembangan koperasi di daerah. Program ini menyediakan bantuan modal hingga Rp 50 juta per koperasi dengan bunga rendah. Pendaftaran dibuka mulai hari ini hingga akhir bulan.',
                'image' => 'news/sample-funding.jpg',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'user_id' => $userIds[0] ?? $userIds[0]
            ],
            [
                'title' => 'Workshop Digital Marketing untuk UMKM',
                'content' => 'Dalam rangka meningkatkan daya saing UMKM di era digital, Dinas Koperasi mengadakan workshop digital marketing. Workshop ini menghadirkan praktisi ahli di bidang digital marketing dan e-commerce. Peserta akan belajar cara memasarkan produk melalui media sosial dan platform online.',
                'image' => 'news/sample-workshop.jpg',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'user_id' => $userIds[1] ?? $userIds[0]
            ],
            [
                'title' => 'Pameran Produk UMKM Lokal Akan Segera Digelar',
                'content' => 'Dinas Koperasi akan menggelar pameran produk UMKM lokal pada bulan depan. Pameran ini bertujuan untuk memperkenalkan produk-produk unggulan dari UMKM binaan kepada masyarakat luas. Diharapkan kegiatan ini dapat meningkatkan omzet dan membuka peluang kerjasama baru.',
                'image' => null,
                'status' => 'draft',
                'published_at' => null,
                'user_id' => $userIds[1] ?? $userIds[0]
            ],
            [
                'title' => 'Sosialisasi Regulasi Koperasi Terbaru',
                'content' => 'Dinas Koperasi mengadakan sosialisasi mengenai regulasi terbaru dalam pengelolaan koperasi. Sosialisasi ini penting untuk memberikan pemahaman kepada pengurus koperasi mengenai kewajiban pelaporan, tata kelola yang baik, dan standar operasional yang harus dipenuhi.',
                'image' => 'news/sample-regulation.jpg',
                'status' => 'published',
                'published_at' => now()->subWeek(),
                'user_id' => $userIds[2] ?? $userIds[0]
            ]
        ];

        foreach ($newsData as $news) {
            News::create($news);
        }
    }
}
