<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'author' => fake()->name(),
            'isbn' => 'BK-' . fake()->unique()->bothify('###-####'),
            'category' => fake()->randomElement(['Mathématiques', 'Français', 'Anglais', 'Sciences', 'Histoire', 'Géographie']),
            'language' => fake()->randomElement(['Français', 'English', 'Bilingual']),
            'total_copies' => fake()->numberBetween(1, 10),
            'shelf_location' => fake()->bothify('Rayon ?#'),
            'loan_duration_days' => fake()->numberBetween(3, 14),
            'late_fee_per_day' => fake()->numberBetween(100, 500),
            'description' => fake()->paragraph(),
            'acquired_at' => fake()->dateTimeBetween('-1 year'),
            'managed_by_id' => User::factory(),
        ];
    }
}
