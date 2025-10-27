<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\BranchClass;
use App\Models\Branch;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RoleAndPermissionSeeder::class);

        // Create super-admin user
        User::firstOrCreate(
            ['email' => 'abdulraof@gmail.com'],
            [
                'name' => 'Abdul Raof',
                'password' => Hash::make('123456789'),
                'is_new' => false,
            ]
        )->assignRole('super-admin');
        
        // Create a principal user if it doesn't exist
        $principal = User::firstOrCreate(
            ['email' => 'principal@example.edu.my'],
            [
                'name' => 'Dr. John Smith',
                'password' => Hash::make('123456789'),
                'is_new' => false,
                'is_active' => true,
            ]
        );
        $principal->assignRole('principal');
        
        // Create a default branch if it doesn't exist
        $branch = Branch::firstOrCreate(
            ['name' => 'Example Branch'],
            [
                'address' => '123 Education St',
                'city' => 'Learning City',
                'postal_code' => '12345',
                'country' => 'Malaysia',
                'principal_id' => $principal->id,
                'established_year' => 2000,
                'description' => 'A leading educational institution committed to excellence in teaching and learning.',
                'status' => true
            ]
        );
        
        // Create a default class if it doesn't exist
        $class = BranchClass::firstOrCreate(
            ['code' => 'G1'],
            [
                'branch_id' => $branch->id,
                'name' => 'Grade 1',
                'curriculum_type' => 'kss',
                'grade_level' => 'form_1',
                'academic_session' => '2025/2026',
                'language_medium' => 'malay',
                'capacity' => 40,
                'description' => 'First grade class',
                'status' => true
            ]
        );
        
        // Seed teachers, students, and then daily reports
        $this->call([
            TeacherSeeder::class,
            StudentSeeder::class,
            DailyReportSeeder::class,
        ]);
    }
}
