<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, check if slug column already exists
        if (!Schema::hasColumn('featured_services', 'slug')) {
            // Update existing records to have valid slugs before adding unique constraint
            $services = DB::table('featured_services')->get();

            foreach ($services as $service) {
                $slug = \Illuminate\Support\Str::slug($service->title);
                $originalSlug = $slug;
                $counter = 1;

                // Make sure slug is unique
                while (DB::table('featured_services')->where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                DB::table('featured_services')
                    ->where('id', $service->id)
                    ->update(['slug' => $slug]);
            }

            // Now add the unique constraint
            Schema::table('featured_services', function (Blueprint $table) {
                $table->string('slug')->unique()->after('title');
            });
        }

        // Add other missing columns
        Schema::table('featured_services', function (Blueprint $table) {
            if (!Schema::hasColumn('featured_services', 'content_detail')) {
                $table->longText('content_detail')->nullable()->after('description');
            }
            if (!Schema::hasColumn('featured_services', 'external_link')) {
                $table->string('external_link')->nullable()->after('content_detail');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('featured_services', function (Blueprint $table) {
            if (Schema::hasColumn('featured_services', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('featured_services', 'content_detail')) {
                $table->dropColumn('content_detail');
            }
            if (Schema::hasColumn('featured_services', 'external_link')) {
                $table->dropColumn('external_link');
            }
        });
    }
};
