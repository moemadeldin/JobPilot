<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\AuthException;
use App\Interfaces\Auth\UserValidatorInterface;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use SensitiveParameter;

final readonly class UserValidator implements UserValidatorInterface
{
    public function validateUser(?User $user): void
    {
        throw_unless($user instanceof User, AuthException::class, 'Invalid credentials.', Response::HTTP_BAD_REQUEST);
    }

    public function validateUserIsActive(User $user): void
    {
        throw_unless($user->isActive(), AuthException::class, 'Authentication error.', Response::HTTP_FORBIDDEN);
    }

    public function validateUserCredentials(User $user, #[SensitiveParameter] string $password): void
    {
        /** @var string $hashedPassword */
        $hashedPassword = $user->password;

        throw_unless(Hash::check($password, $hashedPassword), AuthException::class, 'Invalid credentials.', Response::HTTP_BAD_REQUEST);
    }

    public function validateVerificationCode(User $user, string $verificationCode): void
    {
        throw_if($user->verification_code !== $verificationCode, AuthException::class, 'Invalid Code.', Response::HTTP_BAD_REQUEST);
    }
}
