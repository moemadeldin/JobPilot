<?php

declare(strict_types=1);

namespace App\Utilities;

final readonly class Constants
{
    public const string REGISTER_TOKEN_TYPE = 'Register';

    public const string LOGIN_TOKEN_TYPE = 'Login';

    public const string PASSWORD_RESET_TOKEN_TYPE = 'Reset';

    public const string EMAIL_VERIFICATION_TOKEN_TYPE = 'Verify';

    public const string REGISTER_TOKEN_NAME = 'Register Access Token';

    public const string LOGIN_TOKEN_NAME = 'Login Access Token';

    public const string PASSWORD_RESET_TOKEN_NAME = 'Password Reset Token';

    public const string EMAIL_VERIFICATION_TOKEN_NAME = 'Email Verification Token';

    public const int NUMBER_OF_COMPANIES = 3;

    public const int NUMBER_OF_PAGINATED_JOB_VACANCIES = 6;

    public const int MIN_VERIFICATION_CODE = 100_000;

    public const int MAX_VERIFICATION_CODE = 999_999;

    public const int EXPIRATION_VERIFICATION_CODE_TIME_IN_MINUTES = 5;
}
