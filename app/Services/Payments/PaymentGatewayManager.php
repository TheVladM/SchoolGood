<?php

namespace App\Services\Payments;

use App\Enums\PaymentMethod;
use App\Services\Payments\Contracts\MobilePaymentGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    public function gateway(PaymentMethod $method): MobilePaymentGateway
    {
        return match ($method) {
            PaymentMethod::OrangeMoney => app(OrangeMoneyGateway::class),
            PaymentMethod::MtnMomo => app(MtnMomoGateway::class),
            default => throw new InvalidArgumentException('Ce mode ne supporte pas le paiement mobile en ligne.'),
        };
    }
}
