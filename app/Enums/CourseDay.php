<?php

namespace App\Enums;

enum CourseDay: string
{
    case Monday = 'Lundi';
    case Tuesday = 'Mardi';
    case Wednesday = 'Mercredi';
    case Thursday = 'Jeudi';
    case Friday = 'Vendredi';
    case Saturday = 'Samedi';

    public static function values(): array
    {
        return array_map(static fn (self $day) => $day->value, self::cases());
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $day) {
            $options[$day->value] = $day->value;
        }

        return $options;
    }
}
