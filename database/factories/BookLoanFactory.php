<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookLoan;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookLoanFactory extends Factory
{
    protected $model = BookLoan::class;

    public function definition(): array
    {
        $borrowedAt = fake()->dateTimeBetween('-3 months', '-1 day');
        $dueAt = (clone $borrowedAt)->modify('+7 days');
        $returnedAt = fake()->optional(0.7) ? (clone $dueAt)->modify('+' . fake()->numberBetween(-2, 5) . ' days') : null;

        return [
            'book_id' => Book::factory(),
            'student_id' => fake()->optional(0.7) ? Student::factory() : null,
            'user_id' => fake()->optional(0.3) ? User::factory() : null,
            'borrowed_at' => $borrowedAt,
            'due_at' => $dueAt,
            'returned_at' => $returnedAt,
            'daily_penalty_rate' => fake()->numberBetween(100, 500),
            'notes' => fake()->optional()->sentence(),
            'issued_by_id' => User::factory(),
            'returned_by_id' => $returnedAt ? User::factory() : null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'returned_at' => null,
        ]);
    }

    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'returned_at' => now()->subDays(fake()->numberBetween(1, 30)),
            'returned_by_id' => User::factory(),
        ]);
    }
}
