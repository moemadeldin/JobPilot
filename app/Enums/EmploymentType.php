<?php

declare(strict_types=1);

namespace App\Enums;

enum EmploymentType: string
{
    case FULL_TIME = 'full time';
    case REMOTELY = 'remotely';
    case PART_TIME = 'part time';
    case HYBRID = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::FULL_TIME => 'Full-Time',
            self::REMOTELY => 'Remote',
            self::PART_TIME => 'Part-Time',
            self::HYBRID => 'Hybrid',
        };
    }
}
