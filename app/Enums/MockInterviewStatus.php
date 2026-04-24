<?php

declare(strict_types=1);

namespace App\Enums;

enum MockInterviewStatus: string
{
    case QUALIFIED = 'qualified';
    case DISQUALIFIED = 'disqualified';

    public function label(): string
    {
        return match ($this) {
            self::QUALIFIED => 'Qualified',
            self::DISQUALIFIED => 'Disqualified',
        };
    }
}
