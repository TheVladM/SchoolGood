<?php

namespace App\Enums;

enum AnnouncementAudience: string
{
    case AllParents = 'all_parents';
    case Classroom = 'classroom';
    case Parent = 'parent';

    public function label(): string
    {
        return match ($this) {
            self::AllParents => 'Tous les parents',
            self::Classroom => 'Parents d une classe',
            self::Parent => 'Parent d un eleve',
        };
    }

    public static function values(): array
    {
        return array_map(static fn (self $audience) => $audience->value, self::cases());
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $audience) {
            $options[$audience->value] = $audience->label();
        }

        return $options;
    }
}
