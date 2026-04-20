<?php

declare(strict_types=1);

namespace App\Enums;

enum EmploymentType: string
{
    case FULL_TIME = 'full-time';
    case PART_TIME = 'part-time';

    public function label(): string
    {
        return match ($this) {
            self::FULL_TIME => 'Full-Time',
            self::PART_TIME => 'Part-Time',
        };
    }
}
