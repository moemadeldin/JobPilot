<?php

declare(strict_types=1);

namespace App\Interfaces\Auth;

use App\Models\User;

interface TokenManagerInterface
{
    public function createAccessToken(User $user, string $type): string;

    public function deleteAccessToken(User $user): void;
}
