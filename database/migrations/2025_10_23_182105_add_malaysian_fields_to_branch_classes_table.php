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
        Schema::table('branch_classes', function (Blueprint $table) {
            // Malaysian curriculum type (Kurikulum Merdeka, SPM, Form, etc.)
            $table->string('curriculum_type')->default('kss')->after('code');
            
            // Grade level (Tingkatan 1-6 for secondary, or Year 1-6 for primary)
            $table->string('grade_level')->default('form_1')->after('curriculum_type');
            
            // Stream/Stream (Science, Arts, Commerce, Technical, etc.)
            $table->string('stream')->nullable()->after('grade_level');
            
            // Academic session (e.g., 2025/2026)
            $table->string('academic_session')->after('stream');
            
            // Language medium (English, Malay, Chinese, Tamil, etc.)
            $table->string('language_medium')->default('malay')->after('academic_session');
            
            // Maximum capacity
            $table->integer('capacity')->default(40)->after('language_medium');
            
            // Class advisor/teacher in charge
            $table->foreignUuid('advisor_id')->nullable()->constrained('staff')->nullOnDelete()->after('capacity');
            
            // Additional notes
            $table->text('notes')->nullable()->after('advisor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branch_classes', function (Blueprint $table) {
            $table->dropColumn([
                'curriculum_type',
                'grade_level',
                'stream',
                'academic_session',
                'language_medium',
                'capacity',
                'advisor_id',
                'notes',
            ]);
        });
    }
};
