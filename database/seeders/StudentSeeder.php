<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have at least one school and class
        $school = School::firstOrCreate(
            ['name' => 'Example School'],
            ['address' => '123 Education St, Learning City, 12345']
        );

        $class = SchoolClass::firstOrCreate(
            ['code' => 'G1', 'name' => 'Grade 1']
        );

        // Create 30 students
        Student::factory()
            ->count(30)
            ->for($school)
            ->create([
                'class' => $class->code,
            ]);
            
        $this->command->info('Students seeded successfully!');
    }
}
