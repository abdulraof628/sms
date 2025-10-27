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
        Schema::create('staff_attendance', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('staff_id')->constrained('staff')->onDelete('cascade');
            
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->time('expected_clock_out')->nullable();
            
            // Late tracking
            $table->boolean('is_late')->default(false);
            $table->integer('late_minutes')->default(0);
            
            // Overtime tracking
            $table->integer('overtime_minutes')->default(0);
            $table->decimal('overtime_pay', 10, 2)->default(0);
            
            // Status
            $table->enum('status', ['present', 'absent', 'half-day', 'leave'])->default('present');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Unique constraint to prevent duplicate entries for same staff on same date
            $table->unique(['staff_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_attendance');
    }
};
