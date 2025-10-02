<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Auth\TokenManagerInterface;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final class TokenManager implements TokenManagerInterface
{
    private const register = 'Register Access Token';

    private const login = 'Login Access Token';

    private const reset = 'Password Reset Token';

    private const email = 'Email Verification Token';

    public function createAccessToken(User $user, string $type): string
    {
        $tokenName = match ($type) {
            'register' => self::register,
            'login' => self::login,
            'reset' => self::reset,
            'email' => self::email,
        };

        return $user->access_token = $user->createToken($tokenName)->plainTextToken;
    }

    public function deleteAccessToken(#[CurrentUser] User $user): void
    {
        $user->tokens()->delete();
    }
}
