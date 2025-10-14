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
            // Add photo field
            $table->string('photo')->nullable()->after('color');

            // Remove icon field
            $table->dropColumn('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structures', function (Blueprint $table) {
            // Remove photo field
            $table->dropColumn('photo');

            // Add back icon field
            $table->string('icon')->default('fas fa-user')->after('color');
        });
    }
};
