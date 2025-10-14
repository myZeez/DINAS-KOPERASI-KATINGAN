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
        Schema::table('profiles', function (Blueprint $table) {
            $table->text('tujuan')->nullable()->after('quotes');
            $table->text('tentang')->nullable()->after('tujuan');
            $table->text('tugas_pokok')->nullable()->after('tentang');
            $table->text('peran')->nullable()->after('tugas_pokok');
            $table->text('fokus_utama')->nullable()->after('peran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['tujuan', 'tentang', 'tugas_pokok', 'peran', 'fokus_utama']);
        });
    }
};
