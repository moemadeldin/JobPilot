<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\Auth\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class AuthRepository implements AuthRepositoryInterface
{
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::create($data);
            $user->profile()->create();

            return $user;
        });
    }

    public function getUserByEmail(string $email): User
    {
        return User::whereEmail($email)->first();
    }
}