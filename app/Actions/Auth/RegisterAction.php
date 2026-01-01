<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\Auth\RegisterDTO;
use App\Interfaces\Auth\TokenManagerInterface;
use App\Models\User;
use App\Utilities\Constants;
use Illuminate\Support\Facades\DB;

final readonly class RegisterAction
{
    public function __construct(
        private TokenManagerInterface $tokenManager,
    ) {}

    public function handle(RegisterDTO $dto): User
    {
        return DB::transaction(function () use ($dto): User {
            $user = User::query()->create($dto->toArray());
            $this->tokenManager->createAccessToken($user, Constants::REGISTER_TOKEN_TYPE);

            return $user;
        });
    }
}
