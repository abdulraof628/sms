<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = ['admin', 'principal', 'teacher'];
        
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'zip' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'photo' => null,
            'employee_id' => 'EMP' . $this->faker->unique()->numberBetween(1000, 9999),
            'position' => $this->faker->jobTitle(),
            'department' => $this->faker->randomElement(['Mathematics', 'Science', 'English', 'History', 'Arts', 'Sports']),
            'hire_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'salary' => $this->faker->randomFloat(2, 2000, 8000),
            'role' => $this->faker->randomElement($roles),
            'shift_start' => '09:00',
            'shift_end' => '17:00',
            'weekly_hours' => 40,
            'working_days' => [1, 2, 3, 4, 5], // Monday to Friday
            'annual_leave_balance' => $this->faker->numberBetween(10, 20),
            'sick_leave_balance' => $this->faker->numberBetween(5, 15),
            'casual_leave_balance' => $this->faker->numberBetween(5, 10),
            'overtime_enabled' => $this->faker->boolean(70), // 70% chance of overtime enabled
            'overtime_rate' => 1.5,
            'total_overtime_minutes' => 0,
            'is_active' => true,
        ];
    }
}
