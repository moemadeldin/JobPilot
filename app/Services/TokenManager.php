<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Auth\TokenManagerInterface;
use App\Models\User;

final class TokenManager implements TokenManagerInterface
{
    private const personal = 'Personal Access Token';

    private const reset = 'Password Reset Token';

    private const email = 'Email Verification Token';

    public function createAccessToken(User $user, string $type): string
    {
        $tokenName = match ($type) {
            'personal' => self::personal,
            'reset' => self::reset,
            'email' => self::email,
        };

        return $user->access_token = $user->createToken($tokenName)->plainTextToken;
    }

    public function deleteAccessToken(User $user): void
    {
        $user->tokens()->delete();
    }
}
