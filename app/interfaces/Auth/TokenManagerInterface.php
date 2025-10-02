<?php

declare(strict_types=1);

namespace App\Interfaces\Auth;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

interface TokenManagerInterface
{
    public function createAccessToken(User $user, string $type): string;

    public function deleteAccessToken(#[CurrentUser] User $user): void;
}
