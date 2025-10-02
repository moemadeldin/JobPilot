<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Messages\Auth\ValidateMessages;
use App\Enums\Status;
use App\Exceptions\AuthException;
use App\Interfaces\Auth\UserValidatorInterface;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

final class UserValidator implements UserValidatorInterface
{
    public function validateUser(User $user): void
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
        if ($user->status === Status::BLOCKED->value) {
            throw new AuthException(
                ValidateMessages::BLOCKED->value, Response::HTTP_FORBIDDEN
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
