<?php

namespace App\Enums;

enum SchoolYearStatus: string
{
    case Planned = 'planned';
    case Current = 'current';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Planned => 'Preparee',
            self::Current => 'En cours',
            self::Closed => 'Cloturee',
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
