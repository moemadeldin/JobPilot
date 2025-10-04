<?php

declare(strict_types=1);

namespace App\Enums\Messages\Auth;

enum ValidateMessages: string
{
    case AUTH_ERROR = 'Authentication error.';
    case INVALID_CREDENTIALS = 'Invalid credentials.';
}
