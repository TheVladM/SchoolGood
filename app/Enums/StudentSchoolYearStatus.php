<?php

namespace App\Enums;

enum StudentSchoolYearStatus: string
{
    case Active = 'active';
    case Promoted = 'promoted';
    case PreRegistered = 'pre_registered';
    case Withdrawn = 'withdrawn';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Actif',
            self::Promoted => 'Promu',
            self::PreRegistered => 'Preinscrit',
            self::Withdrawn => 'Retire',
        };
    }

    public static function values(): array
    {
        return array_map(static fn (self $status) => $status->value, self::cases());
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $status) {
            $options[$status->value] = $status->label();
        }

        return $options;
    }
}
