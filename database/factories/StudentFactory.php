<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\BranchClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $firstName = $this->faker->firstName($gender);
        $lastName = $this->faker->lastName();
        
        return [
            'branch_id' => Branch::factory(),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => strtolower($firstName . '.' . $lastName . '@example.com'),
            'phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->dateTimeBetween('-18 years', '-5 years')->format('Y-m-d'),
            'gender' => $gender,
            'address' => $this->faker->streetAddress(),
            'admission_number' => 'STD' . $this->faker->unique()->numberBetween(1000, 9999),
            'class' => BranchClass::factory(),
            'section' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'roll_number' => $this->faker->unique()->numberBetween(1, 50),
            'parent_name' => $this->faker->name('male' == $gender ? 'female' : 'male') . ' ' . $lastName,
            'parent_phone' => $this->faker->phoneNumber(),
            'parent_email' => 'parent.' . strtolower($lastName) . $this->faker->numberBetween(1, 99) . '@example.com',
            'medical_conditions' => $this->faker->optional(0.2)->sentence(),
            'notes' => $this->faker->optional(0.3)->paragraph(),
            'status' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }
}
