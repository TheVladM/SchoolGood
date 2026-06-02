<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Services\PaymentReceiptService;
use Illuminate\Http\Response;

class PaymentReceiptController extends Controller
{
    public function __construct(private PaymentReceiptService $receipts) {}

    public function __invoke(Payment $payment): Response
    {
        $this->authorize('view', $payment);

        abort_unless($payment->status === PaymentStatus::Paid, 404, 'Reçu disponible uniquement pour les paiements validés.');

        return $this->receipts->download($payment);
    }
}
