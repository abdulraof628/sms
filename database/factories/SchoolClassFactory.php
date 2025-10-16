<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolClass>
 */
class SchoolClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SchoolClass::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grades = ['Pre-K', 'K', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
        $sections = ['A', 'B', 'C', 'D', 'E'];
        
        $grade = $this->faker->randomElement($grades);
        $section = $this->faker->randomElement($sections);
        $code = 'G' . $grade . $section;
        $name = 'Grade ' . $grade . ' - ' . $section;
        
        return [
            'school_id' => School::factory(),
            'code' => $code,
            'name' => $name,
            'description' => 'Class ' . $name,
            'status' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Indicate that the class is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Indicate that the class is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}
