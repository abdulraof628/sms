<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\SchoolClass;
use App\Models\School;
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
        User::firstOrCreate([
            'email' => 'abdulraof@gmail.com',
            'password' => Hash::make('123456789'),
            'is_new' => false,
        ])->assignRole('developer');
        
        // Create a default school if it doesn't exist
        $school = School::firstOrCreate(
            ['name' => 'Example School'],
            [
                'address' => '123 Education St',
                'city' => 'Learning City',
                'postal_code' => '12345',
                'country' => 'Malaysia',
                'phone' => '+60123456789',
                'email' => 'info@example.edu.my',
                'principal_name' => 'Dr. John Smith',
                'established_year' => 2000,
                'description' => 'A leading educational institution committed to excellence in teaching and learning.',
                'status' => true
            ]
        );
        
        // Create a default class if it doesn't exist
        $class = SchoolClass::firstOrCreate(
            ['code' => 'G1'],
            [
                'school_id' => $school->id,
                'name' => 'Grade 1',
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
