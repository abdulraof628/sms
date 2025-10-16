<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 teachers
        Teacher::factory()
            ->count(10)
            ->create();
            
        $this->command->info('Teachers seeded successfully!');
    }
}
