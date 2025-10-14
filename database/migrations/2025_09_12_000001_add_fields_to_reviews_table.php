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
        Schema::table('reviews', function (Blueprint $table) {
            // Core review fields
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->unsignedTinyInteger('rating')->default(0); // 0-5
            $table->text('comment')->nullable();

            // Moderation/visibility
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->string('status')->default('approved'); // pending|approved|rejected
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'rating', 'comment', 'is_visible', 'is_verified', 'status']);
        });
    }
};
