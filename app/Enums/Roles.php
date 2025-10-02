<?php

declare(strict_types=1);

namespace App\Enums;

enum Roles: string
{
    case ADMIN = 'admin';
    case OWNER = 'owner';
    case USER = 'user';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::OWNER => 'Owner',
            self::USER => 'User',
        };
    }
}
