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
        Schema::table('galleries', function (Blueprint $table) {
            $table->string('category')->default('kegiatan')->after('image');
            $table->string('tags')->nullable()->after('category');
            $table->integer('views')->default(0)->after('tags');
            $table->integer('likes')->default(0)->after('views');
            $table->boolean('is_featured')->default(false)->after('likes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['category', 'tags', 'views', 'likes', 'is_featured']);
        });
    }
};
