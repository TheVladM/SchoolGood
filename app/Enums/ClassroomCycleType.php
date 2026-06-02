<?php

namespace App\Enums;

enum ClassroomCycleType: string
{
    case Standard = 'standard';
    case CrecheMixte = 'creche_mixte';
    case CrecheFrancophone = 'creche_francophone';
    case CrecheAnglophone = 'creche_anglophone';
    case MaternelleBilingue = 'maternelle_bilingue';
    case PrimaireBilingue = 'primaire_bilingue';
    case CycleBilingue = 'cycle_bilingue';

    public function label(): string
    {
        return match ($this) {
            self::Standard => 'Standard',
            self::CrecheMixte => 'Crèche mixte',
            self::CrecheFrancophone => 'Crèche francophone',
            self::CrecheAnglophone => 'Crèche anglophone',
            self::MaternelleBilingue => 'Maternelle bilingue',
            self::PrimaireBilingue => 'Primaire bilingue',
            self::CycleBilingue => 'Cycle bilingue',
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
