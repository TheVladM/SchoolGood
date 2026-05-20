<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Homework;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomeworkFactory extends Factory
{
    protected $model = Homework::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'subject' => fake()->randomElement(['Mathématiques', 'Français', 'Anglais', 'Sciences', 'Histoire']),
            'teacher_id' => User::factory(),
            'classroom_id' => Classroom::factory(),
            'due_date' => fake()->dateTimeBetween('+1 day', '+7 days'),
            'status' => fake()->randomElement(['assigned', 'submitted', 'graded']),
            'attachments' => null,
        ];
    }
}
