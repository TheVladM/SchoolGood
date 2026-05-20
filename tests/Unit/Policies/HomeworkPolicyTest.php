<?php

namespace Tests\Unit\Policies;

use App\Enums\UserRole;
use App\Models\Homework;
use App\Models\User;
use App\Models\Classroom;
use App\Policies\HomeworkPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeworkPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}

