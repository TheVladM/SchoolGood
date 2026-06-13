<?php

namespace Database\Factories;

use App\Enums\AnnouncementAudience;
use App\Enums\AnnouncementStatus;
use App\Models\Announcement;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'audience' => fake()->randomElement(AnnouncementAudience::cases()),
            'status' => fake()->randomElement(AnnouncementStatus::cases()),
            'classroom_id' => fake()->randomElement([null, Classroom::factory()]),
            'author_id' => User::factory(),
            'approved_by_id' => fake()->optional() ? User::factory() : null,
            'approved_at' => fake()->optional() ? now() : null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AnnouncementStatus::Approved,
            'approved_by_id' => User::factory(),
            'approved_at' => now(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AnnouncementStatus::Pending,
        ]);
    }

    public function forAllParents(): static
    {
        return $this->state(fn (array $attributes) => [
            'audience' => AnnouncementAudience::AllParents,
            'classroom_id' => null,
        ]);
    }

    public function forClassroom(): static
    {
        return $this->state(fn (array $attributes) => [
            'audience' => AnnouncementAudience::Classroom,
            'classroom_id' => Classroom::factory(),
        ]);
    }
}
