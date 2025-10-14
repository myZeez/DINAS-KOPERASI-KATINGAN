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
        Schema::table('public_contents', function (Blueprint $table) {
            $table->string('section_name');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->json('settings')->nullable(); // For flexible content settings
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_contents', function (Blueprint $table) {
            $table->dropColumn(['section_name', 'title', 'content', 'settings', 'is_active']);
        });
    }
};
