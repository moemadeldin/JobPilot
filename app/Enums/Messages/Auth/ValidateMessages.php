<?php

declare(strict_types=1);

namespace App\Enums\Messages\Auth;

enum ValidateMessages: string
{
    case AUTH_ERROR = 'Authentication error.';
    case USER_IS_NOT_ACTIVE = 'User is not active.';
    case INVALID_CREDENTIALS = 'Invalid credentials.';
    case INCORRECT_CODE = 'Invalid Code.';
}
