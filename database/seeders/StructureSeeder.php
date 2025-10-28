<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Structure;

class StructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        Structure::truncate();

        // 1. KEPALA DINAS
        $kepala = Structure::create([
            'position' => 'Kepala Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten Katingan',
            'name' => 'YODIHEL, S.E., M.Si',
            'nip' => null,
            'rank' => null,
            'level' => 1,
            'parent_id' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 2. SEKRETARIS (PLT dari Kepala Bidang Pengembangan Perdagangan)
        $sekretaris = Structure::create([
            'position' => 'Sekretaris Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten',
            'name' => 'Drs. SETIADY YUYU',
            'nip' => null,
            'rank' => null,
            'level' => 2,
            'parent_id' => $kepala->id,
            'sort_order' => 1,
            'is_active' => true,
            'is_plt' => true,
            'plt_name' => 'Drs. SETIADY YUYU',
            'plt_notes' => 'PLT dari Kepala Bidang Pengembangan Perdagangan',
        ]);

        // 3. KEPALA SUB BAGIAN UMUM DAN KEPEGAWAIAN
        Structure::create([
            'position' => 'Kepala Sub Bagian Umum dan Kepegawaian',
            'name' => 'RITHA IRIANI, S.H',
            'nip' => null,
            'rank' => null,
            'level' => 3,
            'parent_id' => $sekretaris->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 4. KEPALA SUB BAGIAN KEUANGAN DAN PELAPORAN
        Structure::create([
            'position' => 'Kepala Sub Bagian Keuangan dan Pelaporan',
            'name' => 'ESRA, S.Sos',
            'nip' => null,
            'rank' => null,
            'level' => 3,
            'parent_id' => $sekretaris->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 5. KEPALA BIDANG KOPERASI DAN UMKM
        $kabidKoperasi = Structure::create([
            'position' => 'Kepala Bidang Koperasi dan UMKM',
            'name' => 'BENYAMIN FRANKLIN JAKOB, S.E',
            'nip' => null,
            'rank' => null,
            'level' => 2,
            'parent_id' => $kepala->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Anggota Bidang Koperasi dan UMKM
        Structure::create([
            'position' => 'JFT Pengawas Koperasi Ahli Muda',
            'name' => 'AGUS MAULUDIN, S.P., M.P',
            'nip' => null,
            'rank' => null,
            'level' => 3,
            'parent_id' => $kabidKoperasi->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Structure::create([
            'position' => 'JFT Pengawas Koperasi Ahli Muda',
            'name' => 'EKO ADHI NUGROHO, S.E',
            'nip' => null,
            'rank' => null,
            'level' => 3,
            'parent_id' => $kabidKoperasi->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Structure::create([
            'position' => 'JFT Pengawas Koperasi Ahli Muda',
            'name' => 'RAHMAD J. RAHMALI, S.E',
            'nip' => null,
            'rank' => null,
            'level' => 3,
            'parent_id' => $kabidKoperasi->id,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 6. KEPALA BIDANG PENGEMBANGAN PERDAGANGAN (Dijabat oleh Sekretaris sebagai rangkap)
        $kabidPerdagangan = Structure::create([
            'position' => 'Kepala Bidang Pengembangan Perdagangan',
            'name' => 'Drs. SETIADY YUYU',
            'nip' => null,
            'rank' => null,
            'level' => 2,
            'parent_id' => $kepala->id,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Anggota Bidang Pengembangan Perdagangan
        Structure::create([
            'position' => 'JFT Pengawas Perdagangan Ahli Muda',
            'name' => 'KOSONG',
            'nip' => null,
            'rank' => null,
            'level' => 3,
            'parent_id' => $kabidPerdagangan->id,
            'sort_order' => 1,
            'is_active' => false, // Posisi kosong
        ]);

        Structure::create([
            'position' => 'JFT Pengawas Perdagangan Ahli Muda',
            'name' => 'YONI MARIANAE, S.Pi',
            'nip' => null,
            'rank' => null,
            'level' => 3,
            'parent_id' => $kabidPerdagangan->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Structure::create([
            'position' => 'JFT Pengawas Perdagangan Ahli Muda',
            'name' => 'SODERIYANTO, S.H Adv',
            'nip' => null,
            'rank' => null,
            'level' => 3,
            'parent_id' => $kabidPerdagangan->id,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 7. KEPALA BIDANG KEMETROLOGIAN
        $kabidKemetrologian = Structure::create([
            'position' => 'Kepala Bidang Kemetrologian',
            'name' => 'ALEXANDRA SUHARYONO MIKA LAMBANG, S.Si.Pi',
            'nip' => null,
            'rank' => null,
            'level' => 2,
            'parent_id' => $kepala->id,
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // Anggota Bidang Kemetrologian
        Structure::create([
            'position' => 'JFT Penera Ahli Muda',
            'name' => 'MANOGAR SIMANULLANG, S.T',
            'nip' => null,
            'rank' => null,
            'level' => 3,
            'parent_id' => $kabidKemetrologian->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Structure::create([
            'position' => 'JFT Penera Ahli Muda',
            'name' => 'YUBILIANTO, S.T',
            'nip' => null,
            'rank' => null,
            'level' => 3,
            'parent_id' => $kabidKemetrologian->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Structure::create([
            'position' => 'JFT Pengawas Kemetrologian Ahli Muda',
            'name' => 'SUPRIATNA, SKM',
            'nip' => null,
            'rank' => null,
            'level' => 3,
            'parent_id' => $kabidKemetrologian->id,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 8. JPT PERENCANA (Langsung di bawah Kepala Dinas)
        Structure::create([
            'position' => 'Fungsional Perencana Ahli Muda (JPT Perencana)',
            'name' => 'SIRMANIK STEPHAN, A.Md',
            'nip' => null,
            'rank' => null,
            'level' => 2,
            'parent_id' => $kepala->id,
            'sort_order' => 5,
            'is_active' => true,
        ]);
    }
}
