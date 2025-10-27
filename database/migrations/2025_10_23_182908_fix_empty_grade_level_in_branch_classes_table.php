<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update any existing records with empty or null grade_level to the default value
        DB::table('branch_classes')
            ->where('grade_level', '')
            ->orWhereNull('grade_level')
            ->update(['grade_level' => 'form_1']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this as it's just a data fix
    }
};
