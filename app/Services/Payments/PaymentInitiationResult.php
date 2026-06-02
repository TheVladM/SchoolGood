<?php

namespace App\Services\Payments;

class PaymentInitiationResult
{
    public function __construct(
        public bool $success,
        public ?string $redirectUrl = null,
        public ?string $message = null,
        public ?string $operatorReference = null,
    ) {}
}
