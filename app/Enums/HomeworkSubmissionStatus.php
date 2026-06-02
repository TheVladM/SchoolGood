<?php

namespace App\Enums;

enum HomeworkSubmissionStatus: string
{
    case Pending = 'pending';
    case Submitted = 'submitted';
    case Graded = 'graded';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'En attente',
            self::Submitted => 'Rendu',
            self::Graded => 'Noté',
        };
    }
}
