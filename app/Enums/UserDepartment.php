<?php

namespace App\Enums;

enum UserDepartment: string
{
    case Administration = 'administration';
    case Direction = 'direction';
    case Scolarite = 'scolarite';
    case Pension = 'pension';
    case Teaching = 'enseignement';
    case Library = 'bibliotheque';
    case Finance = 'finance';

    public function label(): string
    {
        return match ($this) {
            self::Administration => 'Administration',
            self::Direction => 'Direction',
            self::Scolarite => 'Scolarite',
            self::Pension => 'Pension',
            self::Teaching => 'Enseignement',
            self::Library => 'Bibliotheque',
            self::Finance => 'Finance',
        };
    }

    public static function values(): array
    {
        return array_map(static fn (self $department) => $department->value, self::cases());
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $department) {
            $options[$department->value] = $department->label();
        }

        return $options;
    }
}
