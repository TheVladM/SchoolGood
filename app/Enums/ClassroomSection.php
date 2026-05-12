<?php

namespace App\Enums;

enum ClassroomSection: string
{
    case Francophone = 'francophone';
    case Anglophone = 'anglophone';
    case Bilingue = 'bilingue';

    public function label(): string
    {
        return match ($this) {
            self::Francophone => 'Francophone',
            self::Anglophone => 'Anglophone',
            self::Bilingue => 'Bilingue',
        };
    }

    public static function values(): array
    {
        return array_map(static fn (self $section) => $section->value, self::cases());
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $section) {
            $options[$section->value] = $section->label();
        }

        return $options;
    }
}
