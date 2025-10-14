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
            $table->string('position'); // Jabatan/Posisi
            $table->string('name'); // Nama lengkap
            $table->string('nip')->nullable(); // Nomor Induk Pegawai
            $table->string('email')->nullable(); // Email
            $table->string('phone')->nullable(); // Telepon
            $table->string('rank')->nullable(); // Pangkat/Golongan
            $table->integer('level')->default(1); // Level hierarki (1=tertinggi)
            $table->integer('parent_id')->nullable(); // Parent structure ID
            $table->integer('sort_order')->default(0); // Urutan tampilan
            $table->string('color')->nullable(); // Warna untuk visual
            $table->string('icon')->default('fas fa-user'); // Icon
            $table->boolean('is_active')->default(true); // Status aktif
            $table->text('description')->nullable(); // Deskripsi tugas
            $table->timestamps();
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
