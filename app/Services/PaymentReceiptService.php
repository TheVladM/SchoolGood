<?php

namespace App\Services;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PaymentReceiptService
{
    public function assignReceiptNumber(Payment $payment): Payment
    {
        if ($payment->receipt_number) {
            return $payment;
        }

        $year = now()->format('Y');
        $sequence = Payment::whereYear('paid_at', now()->year)
            ->whereNotNull('receipt_number')
            ->count() + 1;

        try {
            $payment->update([
                'receipt_number' => sprintf('REC-%s-%05d', $year, $sequence),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to assign receipt number: '.$e->getMessage());
            throw $e;
        }

        return $payment->fresh();
    }

    public function download(Payment $payment): Response
    {
        $payment->load(['student.classroom', 'student.parent', 'validatedBy', 'receivedBy']);

        if (! $payment->receipt_number) {
            $this->assignReceiptNumber($payment);
            $payment->refresh();
        }

        try {
            $pdf = Pdf::loadView('payments.receipt.pdf', [
                'payment' => $payment,
                'schoolName' => config('payments.school_name'),
            ])->setPaper('a4');

            $filename = 'recu-'.($payment->receipt_number ?? $payment->id).'.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('PDF generation failed: '.$e->getMessage());
            abort(500, 'Erreur lors de la génération du reçu PDF.');
        }
    }
}
