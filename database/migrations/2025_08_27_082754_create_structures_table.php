<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('structures', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama lengkap pejabat
            $table->string('position'); // Jabatan/Posisi
            $table->string('unit'); // Unit Kerja (Sekretariat, Bidang Koperasi, dll)
            $table->enum('level', ['pimpinan', 'sekretariat', 'bidang', 'subbag', 'fungsional'])->default('fungsional'); // Tingkat jabatan
            $table->enum('type', ['struktural', 'fungsional'])->default('struktural'); // Jenis jabatan
            $table->string('nip')->nullable(); // Nomor Induk Pegawai
            $table->string('rank')->nullable(); // Pangkat/Golongan
            $table->string('education')->nullable(); // Pendidikan (S.E, M.Si, dll)
            $table->string('email')->nullable(); // Email
            $table->string('phone')->nullable(); // Telepon
            $table->string('photo')->nullable(); // Foto pejabat
            $table->integer('parent_id')->nullable(); // Parent structure ID untuk hierarki
            $table->integer('sort_order')->default(0); // Urutan tampilan
            $table->boolean('is_active')->default(true); // Status aktif/kosong
            $table->text('description')->nullable(); // Deskripsi tugas/keterangan
            $table->timestamps();
            $table->softDeletes(); // Untuk fitur trash
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structures');
    }
};
