<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Student;
use App\Models\TuitionFee;

class StudentTuitionService
{
    public function tuitionFeeFor(Student $student): ?TuitionFee
    {
        $student->loadMissing('classroom');

        if (! $student->classroom) {
            return null;
        }

        return TuitionFee::query()
            ->where('level', $student->classroom->level)
            ->where('section', $student->classroom->section?->value)
            ->first();
    }

    public function expectedAmount(Student $student, PaymentType|string $type): ?float
    {
        $fee = $this->tuitionFeeFor($student);

        if (! $fee) {
            return null;
        }

        $paymentType = $type instanceof PaymentType ? $type : PaymentType::from($type);

        return match ($paymentType) {
            PaymentType::Registration => (float) $fee->registration_fee,
            PaymentType::FirstInstallment => (float) $fee->first_installment,
            PaymentType::SecondInstallment => (float) $fee->second_installment,
            PaymentType::ThirdInstallment => (float) $fee->third_installment,
        };
    }

    public function totalAnnualDue(Student $student): float
    {
        return (float) ($this->tuitionFeeFor($student)?->totalAnnualFees() ?? 0);
    }

    public function totalPaid(Student $student): float
    {
        return (float) $student->payments()
            ->where('status', PaymentStatus::Paid->value)
            ->sum('amount');
    }

    public function balanceDue(Student $student): float
    {
        return max(0, $this->totalAnnualDue($student) - $this->totalPaid($student));
    }

    /**
     * @return array<int, array{type: string, label: string, due: float, paid: float, remaining: float}>
     */
    public function installmentBreakdown(Student $student): array
    {
        $fee = $this->tuitionFeeFor($student);

        if (! $fee) {
            return [];
        }

        $paidByType = $student->payments()
            ->where('status', PaymentStatus::Paid->value)
            ->get()
            ->groupBy(fn ($payment) => $payment->type->value)
            ->map(fn ($group) => (float) $group->sum('amount'));

        $rows = [];

        foreach (PaymentType::cases() as $type) {
            $due = $this->expectedAmount($student, $type) ?? 0;
            $paid = (float) ($paidByType[$type->value] ?? 0);

            $rows[] = [
                'type' => $type->value,
                'label' => $type->label(),
                'due' => $due,
                'paid' => $paid,
                'remaining' => max(0, $due - $paid),
            ];
        }

        return $rows;
    }
}
