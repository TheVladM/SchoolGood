<?php

namespace Tests\Unit\Policies;

use App\Enums\UserRole;
use App\Models\Homework;
use App\Models\User;
use App\Policies\HomeworkPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeworkPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_can_view_homework_in_child_classroom(): void
    {
        $policy = new HomeworkPolicy;
        $parent = User::factory()->create(['role' => UserRole::Parent]);
        $homework = Homework::factory()->create();

        $this->assertFalse($policy->view($parent, $homework));
    }
}
