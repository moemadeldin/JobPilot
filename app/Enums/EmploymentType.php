<?php

declare(strict_types=1);

namespace App\Enums;

enum EmploymentType: int
{
    case FULL_TIME = 1;
    case REMOTELY = 2;
    case CONTRACT = 3;
    case HYBRID = 4;

    public function label(): string
    {
        return match ($this) {
            self::FULL_TIME => 'Full-Time',
            self::REMOTELY => 'Remote',
            self::CONTRACT => 'Contract',
            self::HYBRID => 'Hybrid',
        };
    }
}
