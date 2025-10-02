<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: string
{
    case ACTIVE = 'admin';
    case INACTIVE = 'inactive';
    case BLOCKED = 'blocked';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::BLOCKED => 'Blocked',
        };
    }
}
