<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get or create a user with principal role
        $principal = User::role('principal')->inRandomOrder()->first();
        if (!$principal) {
            $principal = User::factory()->create();
            $principal->assignRole('principal');
        }

        return [
            'name' => $this->faker->company() . ' Branch',
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'website' => $this->faker->optional(0.8)->url(),
            'principal_id' => $principal->id,
            'established_year' => $this->faker->numberBetween(1900, date('Y') - 1),
            'description' => $this->faker->optional(0.7)->paragraph(),
            'notes' => $this->faker->optional(0.5)->paragraphs(2, true),
            'status' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Indicate that the branch is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Indicate that the branch is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}

