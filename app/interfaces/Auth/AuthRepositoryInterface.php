<?php

declare(strict_types=1);

namespace App\Interfaces\Auth;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function create(array $data): User;

    public function getUserByEmail(string $email): User;
}
