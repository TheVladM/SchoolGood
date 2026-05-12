<?php

namespace App\Enums;

enum UserRole: string
{
    case Founder = 'fondateur';
    case Admin = 'admin';
    case Scolarite = 'scolarite';
    case Teacher = 'enseignant';
    case Parent = 'parent';

    public function label(): string
    {
        return match ($this) {
            self::Founder => 'Fondateur',
            self::Admin => 'Administrateur',
            self::Scolarite => 'Scolarite',
            self::Teacher => 'Enseignant',
            self::Parent => 'Parent',
        };
    }

    public static function values(): array
    {
        return array_map(static fn (self $role) => $role->value, self::cases());
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $role) {
            $options[$role->value] = $role->label();
        }

        return $options;
    }
}
