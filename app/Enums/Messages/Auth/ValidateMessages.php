<?php

declare(strict_types=1);

namespace App\Enums\Messages\Auth;

enum ValidateMessages: string
{
    case BLOCKED = 'User is blocked.';
    case INVALID_CREDENTIALS = 'Invalid credentials.';
}
