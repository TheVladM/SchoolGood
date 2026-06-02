<?php

namespace Tests\Feature;

use App\Enums\ClassroomSection;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Payment;
use App\Models\SmsLog;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobilePaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_orange_webhook_marks_payment_as_paid(): void
    {
        config(['payments.simulate_webhooks' => true]);

        $parent = User::factory()->create(['role' => UserRole::Parent, 'phone' => '237600000001']);
        $classroom = Classroom::create([
            'name' => 'CM1',
            'level' => 'CM1',
            'section' => ClassroomSection::Francophone->value,
            'room' => 'A1',
        ]);
        $student = Student::create([
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'birth_date' => '2015-01-01',
            'classroom_id' => $classroom->id,
            'parent_id' => $parent->id,
        ]);

        $payment = Payment::create([
            'student_id' => $student->id,
            'type' => PaymentType::FirstInstallment->value,
            'amount' => 50000,
            'method' => PaymentMethod::OrangeMoney->value,
            'status' => PaymentStatus::Pending->value,
            'intent_reference' => 'SG-TEST-ORANGE-001',
            'reference' => 'SG-TEST-ORANGE-001',
            'declared_by_parent' => true,
        ]);

        $this->postJson(route('webhooks.payments.orange'), [
            'order_id' => 'SG-TEST-ORANGE-001',
            'status' => 'SUCCESS',
            'txnid' => 'OM-12345',
        ])->assertOk();

        $payment->refresh();
        $this->assertSame(PaymentStatus::Paid, $payment->status);
        $this->assertNotNull($payment->receipt_number);
        $this->assertNotNull($payment->paid_at);
    }

    public function test_parent_can_download_receipt_after_payment(): void
    {
        $parent = User::factory()->create(['role' => UserRole::Parent]);
        $classroom = Classroom::create([
            'name' => 'CE1',
            'level' => 'CE1',
            'section' => ClassroomSection::Francophone->value,
            'room' => 'B1',
        ]);
        $student = Student::create([
            'first_name' => 'Marie',
            'last_name' => 'Ngono',
            'birth_date' => '2016-05-05',
            'classroom_id' => $classroom->id,
            'parent_id' => $parent->id,
        ]);

        $payment = Payment::create([
            'student_id' => $student->id,
            'type' => PaymentType::FirstInstallment->value,
            'amount' => 25000,
            'method' => PaymentMethod::MtnMomo->value,
            'status' => PaymentStatus::Paid->value,
            'receipt_number' => 'REC-2026-00001',
            'paid_at' => now(),
            'validated_at' => now(),
        ]);

        $this->actingAs($parent)
            ->get(route('payments.receipt', $payment))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_validation_sends_sms_log_when_enabled(): void
    {
        config(['sms.enabled' => true, 'sms.driver' => 'log']);

        $founder = User::factory()->create(['role' => UserRole::Founder]);
        $parent = User::factory()->create(['role' => UserRole::Parent, 'phone' => '237699999999']);
        $classroom = Classroom::create([
            'name' => 'CM2',
            'level' => 'CM2',
            'section' => ClassroomSection::Francophone->value,
            'room' => 'C1',
        ]);
        $student = Student::create([
            'first_name' => 'Paul',
            'last_name' => 'Mba',
            'birth_date' => '2014-03-03',
            'classroom_id' => $classroom->id,
            'parent_id' => $parent->id,
        ]);

        $payment = Payment::create([
            'student_id' => $student->id,
            'type' => PaymentType::FirstInstallment->value,
            'amount' => 10000,
            'method' => PaymentMethod::OrangeMoney->value,
            'status' => PaymentStatus::Pending->value,
        ]);

        $this->actingAs($founder)
            ->post(route('payments.validate', $payment))
            ->assertRedirect();

        $this->assertTrue(SmsLog::where('to', '237699999999')->exists());
    }
}
