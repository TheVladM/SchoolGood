<?php

namespace App\Enums;

enum SchoolLevel: string
{
    case Creche = 'Crèche';
    case PetiteSection = 'Petite section';
    case MoyenneSection = 'Moyenne section';
    case GrandeSection = 'Grande section';
    case Sil = 'SIL';
    case Cp = 'CP';
    case Ce1 = 'CE1';
    case Ce2 = 'CE2';
    case Cm1 = 'CM1';
    case Cm2 = 'CM2';
    case Kindergarten = 'Kindergarten';
    case Nursery1 = 'Nursery 1';
    case Nursery2 = 'Nursery 2';
    case Nursery3 = 'Nursery 3';
    case Class1 = 'Class 1';
    case Class2 = 'Class 2';
    case Class3 = 'Class 3';
    case Class4 = 'Class 4';
    case Class5 = 'Class 5';
    case Class6 = 'Class 6';

    public static function francophoneLevels(): array
    {
        return [
            self::Creche,
            self::PetiteSection,
            self::MoyenneSection,
            self::GrandeSection,
            self::Sil,
            self::Cp,
            self::Ce1,
            self::Ce2,
            self::Cm1,
            self::Cm2,
        ];
    }

    public static function anglophoneLevels(): array
    {
        return [
            self::Kindergarten,
            self::Nursery1,
            self::Nursery2,
            self::Nursery3,
            self::Class1,
            self::Class2,
            self::Class3,
            self::Class4,
            self::Class5,
            self::Class6,
        ];
    }

    public static function optionsForSection(ClassroomSection|string $section): array
    {
        $sectionValue = $section instanceof ClassroomSection ? $section->value : $section;

        $levels = match ($sectionValue) {
            ClassroomSection::Francophone->value => self::francophoneLevels(),
            ClassroomSection::Anglophone->value => self::anglophoneLevels(),
            default => array_merge(self::francophoneLevels(), self::anglophoneLevels()),
        };

        $options = [];
        foreach ($levels as $level) {
            $options[$level->value] = $level->value;
        }

        return $options;
    }

    public static function isValidForSection(string $level, ClassroomSection|string $section): bool
    {
        return array_key_exists($level, self::optionsForSection($section));
    }

    public static function nextLevel(string $currentLevel): ?string
    {
        $map = [
            'crèche' => self::PetiteSection->value,
            'petite section' => self::MoyenneSection->value,
            'moyenne section' => self::GrandeSection->value,
            'grande section' => self::Sil->value,
            'sil' => self::Cp->value,
            'cp' => self::Ce1->value,
            'ce1' => self::Ce2->value,
            'ce2' => self::Cm1->value,
            'cm1' => self::Cm2->value,
            'kindergarten' => self::Nursery1->value,
            'kindergarden' => self::Nursery1->value,
            'nursery 1' => self::Nursery2->value,
            'nursery 2' => self::Nursery3->value,
            'nursery 3' => self::Class1->value,
            'class 1' => self::Class2->value,
            'class 2' => self::Class3->value,
            'class 3' => self::Class4->value,
            'class 4' => self::Class5->value,
            'class 5' => self::Class6->value,
        ];

        return $map[strtolower(trim($currentLevel))] ?? null;
    }
}
