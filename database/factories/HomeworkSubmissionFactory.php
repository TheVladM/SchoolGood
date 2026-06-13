<?php

namespace Database\Factories;

use App\Models\HomeworkSubmission;
use App\Models\Student;
use App\Models\Homework;
use App\Enums\HomeworkSubmissionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomeworkSubmissionFactory extends Factory
{
    protected $model = HomeworkSubmission::class;

    public function definition(): array
    {
        return [
            'homework_id' => Homework::factory(),
            'student_id' => Student::factory(),
            'submitted_at' => fake()->dateTimeBetween('-1 day'),
            'submission_content' => fake()->paragraph(),
            'status' => fake()->randomElement(HomeworkSubmissionStatus::cases()),
            'grade' => fake()->optional(0.7) ? fake()->numberBetween(0, 20) : null,
            'teacher_comments' => fake()->optional()->sentence(),
        ];
    }

    public function graded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => HomeworkSubmissionStatus::Graded,
            'grade' => fake()->numberBetween(10, 20),
            'teacher_comments' => fake()->sentence(),
        ]);
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => HomeworkSubmissionStatus::Submitted,
            'grade' => null,
            'teacher_comments' => null,
        ]);
    }
}
