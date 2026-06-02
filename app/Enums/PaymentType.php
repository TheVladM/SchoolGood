<?php

namespace App\Enums;

enum PaymentType: string
{
    case Registration = 'inscription';
    case FirstInstallment = '1ere_tranche';
    case SecondInstallment = '2eme_tranche';
    case ThirdInstallment = '3eme_tranche';

    public function label(): string
    {
        return match ($this) {
            self::Registration => 'Inscription',
            self::FirstInstallment => '1ère tranche',
            self::SecondInstallment => '2ème tranche',
            self::ThirdInstallment => '3ème tranche',
        };
    }

    public static function values(): array
    {
        return array_map(static fn (self $type) => $type->value, self::cases());
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $type) {
            $options[$type->value] = $type->label();
        }

        return $options;
    }
}
