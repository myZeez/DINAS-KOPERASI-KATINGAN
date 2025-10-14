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
        // Add soft deletes to news table
        Schema::table('news', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to galleries table
        Schema::table('galleries', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to reviews table
        Schema::table('reviews', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to hero_carousels table
        Schema::table('hero_carousels', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to featured_services table
        Schema::table('featured_services', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to public_contents table
        Schema::table('public_contents', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to structures table
        Schema::table('structures', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('hero_carousels', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('featured_services', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('public_contents', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('structures', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
