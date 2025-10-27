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
        Schema::create('staff', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('photo')->nullable();
            
            // Employment details
            $table->string('employee_id')->unique();
            $table->string('position')->nullable(); // Job title/position
            $table->string('department')->nullable();
            $table->date('hire_date')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            
            // Role assignment (Admin, Principal, Teacher)
            $table->enum('role', ['admin', 'principal', 'teacher'])->default('teacher');
            
            // Working hours/shifts
            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();
            $table->integer('weekly_hours')->default(40); // Expected hours per week
            $table->json('working_days')->nullable(); // Array of working days [1,2,3,4,5] for Mon-Fri
            
            // Leave management
            $table->integer('annual_leave_balance')->default(0); // In days
            $table->integer('sick_leave_balance')->default(0); // In days
            $table->integer('casual_leave_balance')->default(0); // In days
            
            // Overtime settings
            $table->boolean('overtime_enabled')->default(false);
            $table->decimal('overtime_rate', 5, 2)->default(1.5); // 1.5x regular rate
            $table->integer('total_overtime_minutes')->default(0); // Track cumulative overtime
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Tenant relationship
            $table->foreignUuid('tenant_id')->nullable()->constrained()->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
