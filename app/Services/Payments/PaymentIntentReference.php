<?php

namespace App\Services\Payments;

use App\Models\Payment;

class PaymentIntentReference
{
    public static function generate(): string
    {
        do {
            $reference = 'SG-'.now()->format('Ymd').'-'.strtoupper(bin2hex(random_bytes(4)));
        } while (Payment::where('intent_reference', $reference)->exists());

        return $reference;
    }
}
