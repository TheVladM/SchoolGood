<?php

namespace App\Services\Payments\Contracts;

use App\Models\Payment;
use App\Services\Payments\PaymentInitiationResult;

interface MobilePaymentGateway
{
    public function initiate(Payment $payment, string $payerPhone): PaymentInitiationResult;

    public function verifyWebhookSignature(string $payload, ?string $signature): bool;
}
