<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Classroom;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_list_or_create_payments(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->get(route('payments.index'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('payments.create'))
            ->assertForbidden();
    }

    public function test_founder_can_validate_pending_payment(): void
    {
        $founder = User::factory()->create(['role' => UserRole::Founder]);
        $classroom = Classroom::create([
            'name' => 'CM2 B',
            'level' => 'CM2',
            'section' => 'francophone',
            'room' => 'B2',
        ]);
        $parent = User::factory()->create(['role' => UserRole::Parent]);
        $student = Student::create([
            'first_name' => 'Awa',
            'last_name' => 'Ndj',
            'birth_date' => '2015-05-05',
            'classroom_id' => $classroom->id,
            'parent_id' => $parent->id,
        ]);
        $payment = Payment::create([
            'student_id' => $student->id,
            'type' => PaymentType::Registration,
            'amount' => 50000,
            'method' => PaymentMethod::OrangeMoney,
            'status' => PaymentStatus::Pending,
            'received_by_id' => $founder->id,
        ]);

        $this->actingAs($founder)
            ->post(route('payments.validate', $payment))
            ->assertRedirect();

        $this->assertSame(PaymentStatus::Paid, $payment->fresh()->status);
    }
}
