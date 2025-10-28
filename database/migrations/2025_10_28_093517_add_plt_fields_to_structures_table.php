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
        Schema::table('structures', function (Blueprint $table) {
            $table->boolean('is_plt')->default(false)->after('is_active'); // Status PLT
            $table->unsignedBigInteger('plt_from_structure_id')->nullable()->after('is_plt'); // ID struktur yang menjabat PLT
            $table->string('plt_name')->nullable()->after('plt_from_structure_id'); // Nama PLT (jika manual/dari luar)
            $table->string('plt_nip')->nullable()->after('plt_name'); // NIP PLT (jika manual)
            $table->string('plt_rank')->nullable()->after('plt_nip'); // Pangkat PLT (jika manual)
            $table->date('plt_start_date')->nullable()->after('plt_rank'); // Tanggal mulai PLT
            $table->date('plt_end_date')->nullable()->after('plt_start_date'); // Tanggal selesai PLT (estimasi)
            $table->text('plt_notes')->nullable()->after('plt_end_date'); // Keterangan PLT

            // Foreign key untuk relasi ke struktur lain
            $table->foreign('plt_from_structure_id')->references('id')->on('structures')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structures', function (Blueprint $table) {
            $table->dropForeign(['plt_from_structure_id']);
            $table->dropColumn([
                'is_plt',
                'plt_from_structure_id',
                'plt_name',
                'plt_nip',
                'plt_rank',
                'plt_start_date',
                'plt_end_date',
                'plt_notes'
            ]);
        });
    }
};
