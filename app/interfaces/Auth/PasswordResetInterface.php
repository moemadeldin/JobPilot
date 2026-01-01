<?php

declare(strict_types=1);

namespace App\Interfaces\Auth;

use App\Models\User;
use SensitiveParameter;

interface PasswordResetInterface
{
    public function forgot(string $email): User;

    public function checkCode(string $email, string $verificationCode): User;

    public function reset(User $user, #[SensitiveParameter] string $password): User;
}
