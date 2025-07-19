<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\Auth\RegisterDTO;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Services\TokenManager;
use Illuminate\Support\Facades\DB;

final class RegisterAction
{
    public function __construct(
        private readonly AuthRepository $authRepository,
        private readonly TokenManager $tokenManager,
    ) {}

    public function handle(RegisterDTO $dto): User
    {
        return DB::transaction(function () use ($dto): User {
            $user = $this->authRepository->create($dto->toArray());
            $this->tokenManager->createAccessToken($user, 'personal');

            return $user;
        });
    }
}
