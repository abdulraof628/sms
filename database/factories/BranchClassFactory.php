<?php

namespace Database\Factories;

use App\Models\BranchClass;
use App\Models\Branch;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BranchClass>
 */
class BranchClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BranchClass::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $curriculumTypes = ['kurikulum_merdeka', 'kss', 'kssm', 'kbsr', 'kbsm', 'spm', 'stpm'];
        $primaryGrades = ['year_1', 'year_2', 'year_3', 'year_4', 'year_5', 'year_6'];
        $secondaryGrades = ['form_1', 'form_2', 'form_3', 'form_4', 'form_5', 'form_6'];
        $streams = ['general', 'science', 'arts', 'commerce', 'technical', 'vocational', 'engineering'];
        $languageMediums = ['malay', 'english', 'bilingual', 'english_malay'];
        
        $allGrades = array_merge($primaryGrades, $secondaryGrades);
        $gradeLevel = $this->faker->randomElement($allGrades);
        
        // Only assign stream for secondary classes
        $stream = str_starts_with($gradeLevel, 'form') 
            ? $this->faker->randomElement($streams) 
            : null;

        return [
            'branch_id' => Branch::factory(),
            'name' => $this->faker->randomElement(['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5', 'Form 6']),
            'code' => strtoupper($this->faker->unique()->bothify('?#-####')),
            'curriculum_type' => $this->faker->randomElement($curriculumTypes),
            'grade_level' => $gradeLevel,
            'stream' => $stream,
            'academic_session' => $this->faker->randomElement(['2024/2025', '2025/2026', '2026/2027']),
            'language_medium' => $this->faker->randomElement($languageMediums),
            'capacity' => $this->faker->numberBetween(30, 50),
            'advisor_id' => $this->faker->optional(0.7)->randomElement(Staff::pluck('id')),
            'description' => $this->faker->optional(0.6)->sentence(),
            'notes' => $this->faker->optional(0.4)->text(100),
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

    /**
     * Indicate that the class is a primary class.
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade_level' => $this->faker->randomElement(['year_1', 'year_2', 'year_3', 'year_4', 'year_5', 'year_6']),
            'stream' => null,
            'curriculum_type' => $this->faker->randomElement(['kbsr', 'kurikulum_merdeka']),
        ]);
    }

    /**
     * Indicate that the class is a secondary class.
     */
    public function secondary(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade_level' => $this->faker->randomElement(['form_1', 'form_2', 'form_3', 'form_4', 'form_5', 'form_6']),
            'stream' => $this->faker->randomElement(['general', 'science', 'arts', 'commerce']),
            'curriculum_type' => $this->faker->randomElement(['kssm', 'kurikulum_merdeka', 'spm']),
        ]);
    }

    /**
     * Indicate that the class is a science stream.
     */
    public function science(): static
    {
        return $this->secondary()->state(fn (array $attributes) => [
            'stream' => 'science',
        ]);
    }

    /**
     * Indicate that the class is an arts stream.
     */
    public function arts(): static
    {
        return $this->secondary()->state(fn (array $attributes) => [
            'stream' => 'arts',
        ]);
    }
}

