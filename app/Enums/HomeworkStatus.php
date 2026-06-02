<?php

namespace App\Enums;

enum HomeworkStatus: string
{
    case Assigned = 'assigned';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Assigned => 'Assigné',
            self::Closed => 'Clôturé',
        };
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
