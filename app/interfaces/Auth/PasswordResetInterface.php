<?php

declare(strict_types=1);

namespace App\Interfaces\Auth;

use App\Models\User;

interface PasswordResetInterface
{
    public function forgot(string $email): ?User;

    public function checkCode(string $email, string $code): ?User;

    public function reset(User $user, string $password): ?User;
}
