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
        Schema::table('featured_services', function (Blueprint $table) {
            // Service categorization and status
            $table->string('service_category')->nullable()->after('title');
            $table->enum('service_status', ['active', 'inactive', 'maintenance'])->default('active')->after('is_active');

            // Requirements and documents
            $table->text('requirements')->nullable()->after('content_detail');
            $table->text('required_documents')->nullable()->after('requirements');
            $table->text('important_notes')->nullable()->after('required_documents');

            // Procedure and cost information
            $table->text('procedure_steps')->nullable()->after('important_notes');
            $table->decimal('service_fee', 15, 2)->default(0)->after('procedure_steps');
            $table->integer('processing_time')->nullable()->after('service_fee');
            $table->enum('processing_time_unit', ['hari', 'minggu', 'bulan'])->default('hari')->after('processing_time');
            $table->text('service_hours')->nullable()->after('processing_time_unit');
            $table->text('service_location')->nullable()->after('service_hours');

            // Contact information
            $table->string('responsible_person')->nullable()->after('service_location');
            $table->string('phone_number')->nullable()->after('responsible_person');
            $table->string('contact_email')->nullable()->after('phone_number');

            // Additional links
            $table->string('form_download_link')->nullable()->after('external_link');
            $table->string('tutorial_link')->nullable()->after('form_download_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('featured_services', function (Blueprint $table) {
            $table->dropColumn([
                'service_category',
                'service_status',
                'requirements',
                'required_documents',
                'important_notes',
                'procedure_steps',
                'service_fee',
                'processing_time',
                'processing_time_unit',
                'service_hours',
                'service_location',
                'responsible_person',
                'phone_number',
                'contact_email',
                'form_download_link',
                'tutorial_link'
            ]);
        });
    }
};
