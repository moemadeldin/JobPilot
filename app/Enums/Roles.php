<?php

declare(strict_types=1);

namespace App\Enums;

enum Roles: int
{
    case ADMIN = 1;
    case OWNER = 2;

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::OWNER => 'Owner',
        };
    }
}
