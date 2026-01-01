<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Messages\Auth\ValidateMessages;
use App\Exceptions\AuthException;
use App\Interfaces\Auth\UserValidatorInterface;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use SensitiveParameter;

final class UserValidator implements UserValidatorInterface
{
    public function validateUser(?User $user): void
    {
        if (! $user instanceof User) {
            throw new AuthException(
                ValidateMessages::INVALID_CREDENTIALS->value,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function validateUserIsActive(User $user): void
    {
        if (! $user->isActive()) {
            throw new AuthException(
                ValidateMessages::AUTH_ERROR->value, Response::HTTP_FORBIDDEN
            );
        }
    }

    public function validateUserCredentials(User $user, #[SensitiveParameter] string $password): void
    {
        if (! Hash::check($password, $user->password)) {
            throw new AuthException(
                ValidateMessages::INVALID_CREDENTIALS->value, Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function validateVerificationCode(User $user, string $verificationCode): void
    {
        if ($user->verification_code !== $verificationCode) {
            throw new AuthException(ValidateMessages::INCORRECT_CODE->value, Response::HTTP_BAD_REQUEST);
        }
    }
}
