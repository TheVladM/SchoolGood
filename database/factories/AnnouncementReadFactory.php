<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementReadFactory extends Factory
{
    protected $model = AnnouncementRead::class;

    public function definition(): array
    {
        return [
            'announcement_id' => Announcement::factory(),
            'user_id' => User::factory(),
            'read_at' => fake()->dateTimeBetween('-1 month'),
        ];
    }
}
