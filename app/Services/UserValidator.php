<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Messages\Auth\ValidateMessages;
use App\Exceptions\AuthException;
use App\Interfaces\Auth\UserValidatorInterface;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

final class UserValidator implements UserValidatorInterface
{
    public function validateUser(?User $user): void
    {
        if (! $user) {
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

    public function validateUserCredentials(User $user, string $password): void
    {
        if (! Hash::check($password, $user->password)) {
            throw new AuthException(
                ValidateMessages::INVALID_CREDENTIALS->value, Response::HTTP_BAD_REQUEST
            );
        }
    }
}
