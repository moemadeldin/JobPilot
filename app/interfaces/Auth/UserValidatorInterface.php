<?php

declare(strict_types=1);

namespace App\Interfaces\Auth;

use App\Models\User;
use SensitiveParameter;

interface UserValidatorInterface
{
    /**
     * @phpstan-assert User $user
     */
    public function validateUser(?User $user): void;

    public function validateUserIsActive(User $user): void;

    public function validateUserCredentials(User $user, #[SensitiveParameter] string $password): void;

    public function validateVerificationCode(User $user, string $verificationCode): void;
}
