<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\StudentSchoolGrade;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentSchoolGradeFactory extends Factory
{
    protected $model = StudentSchoolGrade::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'subject' => fake()->randomElement(['Mathématiques', 'Français', 'Anglais', 'Sciences', 'Histoire', 'Géographie']),
            'first_term_grade' => fake()->numberBetween(8, 20),
            'second_term_grade' => fake()->numberBetween(8, 20),
            'third_term_grade' => fake()->numberBetween(8, 20),
            'annual_average' => fake()->numberBetween(10, 20),
        ];
    }

    public function excellent(): static
    {
        return $this->state(fn (array $attributes) => [
            'first_term_grade' => fake()->numberBetween(16, 20),
            'second_term_grade' => fake()->numberBetween(16, 20),
            'third_term_grade' => fake()->numberBetween(16, 20),
            'annual_average' => fake()->numberBetween(17, 20),
        ]);
    }

    public function good(): static
    {
        return $this->state(fn (array $attributes) => [
            'first_term_grade' => fake()->numberBetween(12, 16),
            'second_term_grade' => fake()->numberBetween(12, 16),
            'third_term_grade' => fake()->numberBetween(12, 16),
            'annual_average' => fake()->numberBetween(12, 16),
        ]);
    }
}
