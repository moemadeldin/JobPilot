<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\Auth\RegisterDTO;
use App\Interfaces\Auth\TokenManagerInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class RegisterAction
{
    public function __construct(
        private readonly TokenManagerInterface $tokenManager,
    ) {}

    public function handle(RegisterDTO $dto): User
    {
        return DB::transaction(function () use ($dto): User {
            $user = User::create($dto->toArray());
            $this->tokenManager->createAccessToken($user, 'register');

            return $user;
        });
    }
}
