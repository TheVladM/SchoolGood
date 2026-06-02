<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_homepage(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));
    }

    public function test_user_can_log_in_and_reach_dashboard(): void
    {
        User::create([
            'name' => 'Founder',
            'email' => 'founder@example.com',
            'phone' => '+237600001111',
            'role' => UserRole::Founder,
            'password' => Hash::make('password'),
        ]);

        $this->post(route('login.store'), [
            'email' => 'founder@example.com',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Vue fondateur');
    }
}
