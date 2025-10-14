<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the complaints table if it exists
        if (Schema::hasTable('complaints')) {
            Schema::drop('complaints');
        }
    }

    public function down(): void
    {
        // No-op (we won't recreate the complaints table)
    }
};
