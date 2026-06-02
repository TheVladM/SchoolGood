<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\BookLoan;
use App\Models\Payment;
use App\Models\User;

class BookLoanPenaltyService
{
    public function syncOverdueDays(BookLoan $loan): void
    {
        if ($loan->returned_at) {
            return;
        }

        $days = $loan->overdueDays();
        if ($days > $loan->overdue_days_logged) {
            $loan->update(['overdue_days_logged' => $days]);
        }
    }

    public function createPenaltyPayment(BookLoan $loan, User $issuer): ?Payment
    {
        $this->syncOverdueDays($loan);

        $amount = $loan->penaltyAmount();

        if ($amount <= 0 || $loan->penalty_payment_id) {
            return $loan->penaltyPayment;
        }

        if (! $loan->student_id) {
            return null;
        }

        $payment = Payment::create([
            'student_id' => $loan->student_id,
            'type' => PaymentType::Registration,
            'amount' => $amount,
            'method' => PaymentMethod::OrangeMoney,
            'status' => PaymentStatus::Pending,
            'reference' => 'PENALITE-BIB-'.$loan->id,
            'notes' => 'Pénalité bibliothèque — '.$loan->overdue_days_logged.' jour(s) de retard',
            'received_by_id' => $issuer->id,
            'declared_by_parent' => false,
        ]);

        $loan->update(['penalty_payment_id' => $payment->id]);

        return $payment;
    }
}
