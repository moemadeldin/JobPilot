<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Auth\TokenManagerInterface;
use App\Models\User;
use App\Utilities\Constants;
use Illuminate\Container\Attributes\CurrentUser;

final class TokenManager implements TokenManagerInterface
{
    public function createAccessToken(User $user, string $type): string
    {
        $tokenName = match ($type) {
            Constants::REGISTER_TOKEN_TYPE => Constants::REGISTER_TOKEN_TYPE,
            Constants::LOGIN_TOKEN_TYPE => Constants::LOGIN_TOKEN_TYPE,
            Constants::PASSWORD_RESET_TOKEN_TYPE => Constants::PASSWORD_RESET_TOKEN_TYPE,
            Constants::EMAIL_VERIFICATION_TOKEN_TYPE => Constants::EMAIL_VERIFICATION_TOKEN_TYPE,
        };

        return $user->access_token = $user->createToken($tokenName)->plainTextToken;
    }

    public function deleteAccessToken(#[CurrentUser] User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
