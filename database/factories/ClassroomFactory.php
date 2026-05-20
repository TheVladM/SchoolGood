<?php

namespace Database\Factories;

use App\Enums\ClassroomSection;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassroomFactory extends Factory
{
    protected $model = Classroom::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' ' . fake()->randomElement(['A', 'B', 'C']),
            'level' => fake()->randomElement(['Crèche', 'PS', 'MS', 'GS', 'SIL', 'CP', 'CE1', 'CE2', 'CM1', 'CM2']),
            'section' => fake()->randomElement(ClassroomSection::cases()),
            'room' => fake()->bothify('B##'),
            'location' => fake()->buildingNumber() . ' Rue ' . fake()->lastName(),
            'main_teacher_id' => User::factory(),
            'language_teacher_id' => User::factory(),
        ];
    }
}
