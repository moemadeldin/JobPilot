<?php

declare(strict_types=1);

namespace App\Enums\Messages\Auth;

enum SuccessMessages: string
{
    case REGISTERED = 'User Registered Successfully.';
    case LOGGED_IN = 'User Logged in Successfully.';
    case COMPANY_CREATED = 'Company Created Successfully.';
    case COMPANY_UPDATED = 'Company Updated Successfully.';
}
