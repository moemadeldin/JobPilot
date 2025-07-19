<?php

declare(strict_types=1);

namespace App\Interfaces\Auth;

use App\Models\User;

interface UserValidatorInterface
{
    public function validateUser(User $user): void;

    public function validateUserIsActive(User $user): void;

    public function validateUserCredentials(User $user, string $password): void;
}
