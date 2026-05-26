<?php

namespace App\Enums;

enum TeacherLanguage: string
{
    case French = 'french';
    case English = 'english';
    case Bilingual = 'bilingual';

    public function label(): string
    {
        return match ($this) {
            self::French => 'Français',
            self::English => 'Anglais',
            self::Bilingual => 'Bilingue',
        };
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $language) {
            $options[$language->value] = $language->label();
        }

        return $options;
    }
}
