<?php

namespace Database\Factories;

use App\Enums\CourseDay;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'content' => fake()->paragraph(),
            'teacher_id' => User::factory(),
            'classroom_id' => Classroom::factory(),
            'day' => fake()->randomElement(CourseDay::cases()),
        ];
    }
}
