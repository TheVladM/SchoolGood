<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'type' => fake()->randomElement(PaymentType::values()),
            'amount' => fake()->numberBetween(20000, 100000),
            'method' => fake()->randomElement(PaymentMethod::values()),
            'reference' => 'REF-' . fake()->unique()->numerify('######'),
            'account_reference' => fake()->optional()->numerify('237##########'),
            'status' => fake()->randomElement(PaymentStatus::values()),
            'notes' => fake()->optional()->sentence(),
            'received_by_id' => User::factory(),
            'validated_by_id' => fake()->optional() ? User::factory() : null,
            'validated_at' => fake()->optional() ? now() : null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Paid,
            'validated_by_id' => User::factory(),
            'validated_at' => now(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Pending,
        ]);
    }
}
