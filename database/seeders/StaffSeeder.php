<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\Tenant;
use App\Models\StaffAttendance;
use Carbon\Carbon;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first tenant
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $this->command->warn('No tenant found. Please create a tenant first.');
            return;
        }

        // Create 15 staff members
        $staff = [];
        
        // Create an admin
        $staff[] = Staff::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.johnson@school.com',
            'phone' => '+1-555-0101',
            'address' => '123 Main Street',
            'city' => 'New York',
            'state' => 'NY',
            'zip' => '10001',
            'country' => 'USA',
            'employee_id' => 'EMP1001',
            'position' => 'School Administrator',
            'department' => 'Administration',
            'hire_date' => Carbon::now()->subYears(5),
            'salary' => 6500.00,
            'role' => 'admin',
            'shift_start' => '08:00',
            'shift_end' => '16:00',
            'weekly_hours' => 40,
            'working_days' => [1, 2, 3, 4, 5],
            'annual_leave_balance' => 20,
            'sick_leave_balance' => 15,
            'casual_leave_balance' => 10,
            'overtime_enabled' => true,
            'overtime_rate' => 1.5,
            'total_overtime_minutes' => 240, // 4 hours accumulated
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        // Create a principal
        $staff[] = Staff::create([
            'name' => 'Michael Chen',
            'email' => 'michael.chen@school.com',
            'phone' => '+1-555-0102',
            'address' => '456 Oak Avenue',
            'city' => 'New York',
            'state' => 'NY',
            'zip' => '10002',
            'country' => 'USA',
            'employee_id' => 'EMP1002',
            'position' => 'Principal',
            'department' => 'Administration',
            'hire_date' => Carbon::now()->subYears(8),
            'salary' => 7500.00,
            'role' => 'principal',
            'shift_start' => '07:30',
            'shift_end' => '15:30',
            'weekly_hours' => 40,
            'working_days' => [1, 2, 3, 4, 5],
            'annual_leave_balance' => 22,
            'sick_leave_balance' => 18,
            'casual_leave_balance' => 12,
            'overtime_enabled' => true,
            'overtime_rate' => 2.0,
            'total_overtime_minutes' => 360, // 6 hours accumulated
            'is_active' => true,
            'tenant_id' => $tenant->id,
        ]);

        // Create teachers
        $teachers = [
            ['name' => 'Emily Rodriguez', 'subject' => 'Mathematics', 'email' => 'emily.rodriguez@school.com'],
            ['name' => 'David Kim', 'subject' => 'Science', 'email' => 'david.kim@school.com'],
            ['name' => 'Jessica Williams', 'subject' => 'English', 'email' => 'jessica.williams@school.com'],
            ['name' => 'Robert Brown', 'subject' => 'History', 'email' => 'robert.brown@school.com'],
            ['name' => 'Amanda Davis', 'subject' => 'Arts', 'email' => 'amanda.davis@school.com'],
            ['name' => 'James Wilson', 'subject' => 'Physical Education', 'email' => 'james.wilson@school.com'],
            ['name' => 'Lisa Martinez', 'subject' => 'Music', 'email' => 'lisa.martinez@school.com'],
            ['name' => 'Daniel Garcia', 'subject' => 'Computer Science', 'email' => 'daniel.garcia@school.com'],
            ['name' => 'Maria Anderson', 'subject' => 'Chemistry', 'email' => 'maria.anderson@school.com'],
            ['name' => 'Christopher Lee', 'subject' => 'Physics', 'email' => 'christopher.lee@school.com'],
            ['name' => 'Jennifer Taylor', 'subject' => 'Biology', 'email' => 'jennifer.taylor@school.com'],
            ['name' => 'Matthew Thomas', 'subject' => 'Geography', 'email' => 'matthew.thomas@school.com'],
            ['name' => 'Ashley Moore', 'subject' => 'French', 'email' => 'ashley.moore@school.com'],
        ];

        foreach ($teachers as $index => $teacher) {
            $empNum = 1003 + $index;
            $staff[] = Staff::create([
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'phone' => '+1-555-' . str_pad($empNum, 4, '0', STR_PAD_LEFT),
                'address' => fake()->streetAddress(),
                'city' => 'New York',
                'state' => 'NY',
                'zip' => fake()->postcode(),
                'country' => 'USA',
                'employee_id' => 'EMP' . $empNum,
                'position' => $teacher['subject'] . ' Teacher',
                'department' => $teacher['subject'],
                'hire_date' => Carbon::now()->subYears(rand(1, 7)),
                'salary' => rand(3500, 5500),
                'role' => 'teacher',
                'shift_start' => '09:00',
                'shift_end' => '17:00',
                'weekly_hours' => 40,
                'working_days' => [1, 2, 3, 4, 5],
                'annual_leave_balance' => rand(10, 20),
                'sick_leave_balance' => rand(8, 15),
                'casual_leave_balance' => rand(5, 10),
                'overtime_enabled' => rand(0, 1) == 1,
                'overtime_rate' => 1.5,
                'total_overtime_minutes' => rand(0, 480), // 0 to 8 hours
                'is_active' => rand(0, 10) > 1, // 90% active
                'tenant_id' => $tenant->id,
            ]);
        }

        $this->command->info('Created ' . count($staff) . ' staff members.');

        // Create attendance records for the last 7 days for each staff member
        $this->command->info('Creating attendance records...');
        
        foreach ($staff as $staffMember) {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }

                // 90% attendance rate
                if (rand(1, 10) <= 9) {
                    $clockIn = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $staffMember->shift_start);
                    $clockOut = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $staffMember->shift_end);
                    
                    // 30% chance of being late (1-30 minutes)
                    $isLate = rand(1, 10) <= 3;
                    if ($isLate) {
                        $lateMinutes = rand(5, 30);
                        $clockIn->addMinutes($lateMinutes);
                    }
                    
                    // 40% chance of working overtime (15-120 minutes)
                    $hasOvertime = rand(1, 10) <= 4 && $staffMember->overtime_enabled;
                    if ($hasOvertime) {
                        $overtimeMinutes = rand(15, 120);
                        $clockOut->addMinutes($overtimeMinutes);
                    }
                    
                    $attendance = StaffAttendance::create([
                        'staff_id' => $staffMember->id,
                        'date' => $date,
                        'clock_in' => $clockIn,
                        'clock_out' => $clockOut,
                        'status' => 'present',
                    ]);
                    
                    // Calculate overtime and lateness
                    $attendance->expected_clock_out = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $staffMember->shift_end);
                    $attendance->save();
                    $attendance->calculateOvertimeAndLateness();
                } else {
                    // Absent or on leave
                    StaffAttendance::create([
                        'staff_id' => $staffMember->id,
                        'date' => $date,
                        'status' => rand(0, 1) == 1 ? 'absent' : 'leave',
                    ]);
                }
            }
        }

        $this->command->info('Attendance records created successfully!');
    }
}
