<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case OrangeMoney = 'orange_money';
    case MtnMomo = 'mtn_momo';
    case Bank = 'bank';

    public function label(): string
    {
        return match ($this) {
            self::OrangeMoney => 'Orange Money',
            self::MtnMomo => 'MTN MoMo',
            self::Bank => 'Banque',
        };
    }

    public static function values(): array
    {
        return array_map(static fn (self $method) => $method->value, self::cases());
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $method) {
            $options[$method->value] = $method->label();
        }

        return $options;
    }
}
