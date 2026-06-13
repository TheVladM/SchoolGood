<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'birth_date' => fake()->dateTimeBetween('-18 years', '-5 years'),
            'classroom_id' => Classroom::factory(),
            'parent_id' => User::factory(),
            'is_active' => true,
            'left_at' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'left_at' => now(),
        ]);
    }
}
