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
        // Level 1 - Kepala Dinas
        $kepala = Structure::create([
            'position' => 'Kepala Dinas',
            'name' => 'Dr. Ahmad Surya, M.Si',
            'nip' => '196512031990031002',
            'email' => 'ahmad.surya@dinaskoperasi.go.id',
            'phone' => '+62 812-3456-7890',
            'rank' => 'Pembina Utama Muda',
            'level' => 1,
            'parent_id' => null,
            'sort_order' => 1,
            'color' => '#ff6b6b',
            'photo' => null,
            'description' => 'Memimpin dan mengelola seluruh kegiatan Dinas Koperasi',
            'is_active' => true
        ]);

        // Level 2 - Sekretaris
        $sekretaris = Structure::create([
            'position' => 'Sekretaris',
            'name' => 'Siti Rahayu, S.Sos',
            'nip' => '197803142005012009',
            'email' => 'siti.rahayu@dinaskoperasi.go.id',
            'phone' => '+62 813-4567-8901',
            'rank' => 'Penata Tingkat I',
            'level' => 2,
            'parent_id' => $kepala->id,
            'sort_order' => 1,
            'color' => '#00ff88',
            'photo' => null,
            'description' => 'Mengelola administrasi dan kesekretariatan dinas',
            'is_active' => true
        ]);

        // Level 2 - Kabid Koperasi
        $kabidKoperasi = Structure::create([
            'position' => 'Kabid Koperasi',
            'name' => 'Budi Santoso, S.E',
            'nip' => '198205102008011003',
            'email' => 'budi.santoso@dinaskoperasi.go.id',
            'phone' => '+62 814-5678-9012',
            'rank' => 'Penata Tingkat I',
            'level' => 2,
            'parent_id' => $kepala->id,
            'sort_order' => 2,
            'color' => '#ffd93d',
            'photo' => null,
            'description' => 'Mengelola bidang koperasi dan pembinaan anggota',
            'is_active' => true
        ]);

        // Level 2 - Kabid UMKM
        $kabidUMKM = Structure::create([
            'position' => 'Kabid UMKM',
            'name' => 'Maya Sari, S.T',
            'nip' => '199001152015032001',
            'email' => 'maya.sari@dinaskoperasi.go.id',
            'phone' => '+62 815-6789-0123',
            'rank' => 'Penata',
            'level' => 2,
            'parent_id' => $kepala->id,
            'sort_order' => 3,
            'color' => '#a8e6cf',
            'photo' => null,
            'description' => 'Mengelola bidang UMKM dan perdagangan',
            'is_active' => true
        ]);

        // Level 3 - Staff Sekretaris
        Structure::create([
            'position' => 'Staff Keuangan',
            'name' => 'Andi Wijaya, S.E',
            'nip' => '199506112019031005',
            'email' => 'andi.wijaya@dinaskoperasi.go.id',
            'phone' => '+62 816-7890-1234',
            'rank' => 'Penata Muda',
            'level' => 3,
            'parent_id' => $sekretaris->id,
            'sort_order' => 1,
            'color' => '#ddd',
            'photo' => null,
            'description' => 'Mengelola keuangan dan pembukuan dinas',
            'is_active' => true
        ]);

        Structure::create([
            'position' => 'Staff Kepegawaian',
            'name' => 'Dewi Lestari, S.Sos',
            'nip' => '199203082017032002',
            'email' => 'dewi.lestari@dinaskoperasi.go.id',
            'phone' => '+62 817-8901-2345',
            'rank' => 'Penata Muda',
            'level' => 3,
            'parent_id' => $sekretaris->id,
            'sort_order' => 2,
            'color' => '#ddd',
            'photo' => null,
            'description' => 'Mengelola administrasi kepegawaian',
            'is_active' => true
        ]);

        // Level 3 - Staff Koperasi
        Structure::create([
            'position' => 'Staff Pembinaan',
            'name' => 'Rudi Hartono, S.P',
            'nip' => '199408052018011007',
            'email' => 'rudi.hartono@dinaskoperasi.go.id',
            'phone' => '+62 818-9012-3456',
            'rank' => 'Penata Muda',
            'level' => 3,
            'parent_id' => $kabidKoperasi->id,
            'sort_order' => 1,
            'color' => '#ddd',
            'photo' => null,
            'description' => 'Pembinaan dan pelatihan koperasi',
            'is_active' => true
        ]);

        Structure::create([
            'position' => 'Staff Monitoring',
            'name' => 'Lisa Permata, S.E',
            'nip' => '199701122020032003',
            'email' => 'lisa.permata@dinaskoperasi.go.id',
            'phone' => '+62 819-0123-4567',
            'rank' => 'Penata Muda',
            'level' => 3,
            'parent_id' => $kabidKoperasi->id,
            'sort_order' => 2,
            'color' => '#ddd',
            'photo' => null,
            'description' => 'Monitoring dan evaluasi koperasi',
            'is_active' => true
        ]);

        // Level 3 - Staff UMKM
        Structure::create([
            'position' => 'Staff Pengembangan',
            'name' => 'Agus Prasetyo, S.T',
            'nip' => '199512152019031008',
            'email' => 'agus.prasetyo@dinaskoperasi.go.id',
            'phone' => '+62 820-1234-5678',
            'rank' => 'Penata Muda',
            'level' => 3,
            'parent_id' => $kabidUMKM->id,
            'sort_order' => 1,
            'color' => '#ddd',
            'photo' => null,
            'description' => 'Pengembangan UMKM dan inovasi',
            'is_active' => true
        ]);

        Structure::create([
            'position' => 'Staff Pemasaran',
            'name' => 'Nina Handayani, S.E',
            'nip' => '199809032021032004',
            'email' => 'nina.handayani@dinaskoperasi.go.id',
            'phone' => '+62 821-2345-6789',
            'rank' => 'Penata Muda',
            'level' => 3,
            'parent_id' => $kabidUMKM->id,
            'sort_order' => 2,
            'color' => '#ddd',
            'photo' => null,
            'description' => 'Pemasaran dan promosi UMKM',
            'is_active' => true
        ]);
    }
}
