<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\BranchClass;
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
        // Ensure we have at least one branch and class
        $branch = Branch::firstOrCreate(
            ['name' => 'Example Branch'],
            ['address' => '123 Education St, Learning City, 12345']
        );

        $class = BranchClass::firstOrCreate(
            ['code' => 'G1', 'name' => 'Grade 1']
        );

        // Create 30 students
        Student::factory()
            ->count(30)
            ->for($branch)
            ->create([
                'class' => $class->code,
            ]);
            
        $this->command->info('Students seeded successfully!');
    }
}
