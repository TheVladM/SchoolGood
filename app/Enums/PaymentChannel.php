<?php

namespace App\Enums;

enum PaymentChannel: string
{
    case Manual = 'manual';
    case Declared = 'declared';
    case OrangeMoney = 'orange_money';
    case MtnMomo = 'mtn_momo';

    public function label(): string
    {
        return match ($this) {
            self::Manual => 'Guichet / saisie',
            self::Declared => 'Déclaration parent',
            self::OrangeMoney => 'Orange Money (en ligne)',
            self::MtnMomo => 'MTN MoMo (en ligne)',
        };
    }
}
