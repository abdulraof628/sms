<?php

use App\Models\Student;
use App\Models\Teacher;
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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('teacher_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('report');
            $table->string('status')->default('draft');
            $table->timestamps();
            
            // Add index for faster lookups
            $table->index(['student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
