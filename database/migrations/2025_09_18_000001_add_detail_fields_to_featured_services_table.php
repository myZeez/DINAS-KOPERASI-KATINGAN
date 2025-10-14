<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('featured_services', function (Blueprint $table) {
            if (!Schema::hasColumn('featured_services', 'slug')) {
                $table->string('slug')->unique()->after('title');
            }
            if (!Schema::hasColumn('featured_services', 'content_detail')) {
                $table->longText('content_detail')->nullable()->after('description');
            }
            if (!Schema::hasColumn('featured_services', 'external_link')) {
                $table->string('external_link')->nullable()->after('link');
            }
        });
    }

    public function down(): void
    {
        Schema::table('featured_services', function (Blueprint $table) {
            if (Schema::hasColumn('featured_services', 'slug')) {
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
