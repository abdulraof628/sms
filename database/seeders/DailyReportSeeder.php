<?php

namespace Database\Seeders;

use App\Models\DailyReport;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DailyReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students and teachers
        $students = Student::all();
        $teachers = Teacher::all();

        if ($students->isEmpty() || $teachers->isEmpty()) {
            $this->command->warn('No students or teachers found. Please run StudentSeeder and TeacherSeeder first.');
            return;
        }

        // Create reports for the last 30 days for each student
        $reports = [];
        $now = now();
        
        foreach ($students as $student) {
            // Select a random teacher for this student's reports
            $teacher = $teachers->random();
            
            // Create reports for the last 30 days (not all days, to simulate real usage)
            for ($i = 0; $i < 30; $i++) {
                // Skip some days randomly (about 30% of the time)
                if (rand(1, 10) <= 3) {
                    continue;
                }
                
                $date = $now->copy()->subDays(rand(0, 29));
                $status = $this->getRandomStatus();
                
                $reports[] = [
                    'id' => (string) \Illuminate\Support\Str::ulid(),
                    'student_id' => $student->id,
                    'teacher_id' => $teacher->id,
                    'date' => $date->format('Y-m-d'),
                    'report' => $this->generateReportContent($student->first_name),
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Add some randomness to the date
                $date->subDays(rand(1, 3));
            }
        }
        
        // Insert all reports in a batch
        if (!empty($reports)) {
            // Use chunking to avoid memory issues with large datasets
            foreach (array_chunk($reports, 100) as $chunk) {
                DB::table('daily_reports')->insert($chunk);
            }
        }
        
        $this->command->info(sprintf('Seeded %d daily reports!', count($reports)));
    }
    
    /**
     * Get a random report status, weighted towards more common statuses
     */
    private function getRandomStatus(): string
    {
        $statuses = [
            'draft' => 2,
            'submitted' => 3,
            'approved' => 4,
            'rejected' => 1,
        ];
        
        $total = array_sum($statuses);
        $random = rand(1, $total);
        $current = 0;
        
        foreach ($statuses as $status => $weight) {
            $current += $weight;
            if ($random <= $current) {
                return $status;
            }
        }
        
        return 'submitted'; // Fallback
    }
    
    /**
     * Generate realistic report content
     */
    private function generateReportContent(string $studentName): string
    {
        $activities = [
            'participated in class discussions',
            'completed all assigned work',
            'needed some assistance with the lesson',
            'worked well independently',
            'showed improvement in their work',
            'was very engaged in the lesson',
            'needed reminders to stay on task',
            'helped other students',
            'asked thoughtful questions',
            'needed extra time to complete tasks',
        ];
        
        $sentences = [];
        $numSentences = rand(2, 5);
        
        for ($i = 0; $i < $numSentences; $i++) {
            $activity = $activities[array_rand($activities)];
            $sentences[] = ucfirst($studentName) . ' ' . $activity . '.';
        }
        
        // Add a final comment
        $comments = [
            'Keep up the good work!',
            'Looking forward to seeing more progress.',
            'Please encourage more participation in class.',
            'Great effort today!',
            'Let me know if you have any questions.',
        ];
        
        $sentences[] = $comments[array_rand($comments)];
        
        return implode(' ', $sentences);
    }
}
