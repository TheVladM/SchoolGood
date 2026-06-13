<?php

namespace Database\Factories;

use App\Enums\ClassroomSection;
use App\Models\TuitionFee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TuitionFeeFactory extends Factory
{
    protected $model = TuitionFee::class;

    public function definition(): array
    {
        return [
            'level' => fake()->randomElement(['Crèche', 'PS', 'MS', 'GS', 'SIL', 'CP', 'CE1', 'CE2', 'CM1', 'CM2']),
            'section' => fake()->randomElement(ClassroomSection::cases()),
            'registration_fee' => fake()->numberBetween(25000, 50000),
            'first_installment' => fake()->numberBetween(40000, 80000),
            'second_installment' => fake()->numberBetween(40000, 80000),
            'third_installment' => fake()->numberBetween(40000, 80000),
            'notes' => fake()->optional()->sentence(),
            'managed_by_id' => User::factory(),
        ];
    }
}
