<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Services\UserValidator;
use App\Services\TokenManager;

final class LoginAction
{
    public function __construct(
        private readonly AuthRepository $authRepository,
        private readonly TokenManager $tokenManager,
        private readonly UserValidator $userValidator,
    ) {}

    public function handle(LoginDTO $dto): User
    {
        $user = $this->authRepository->getUserByEmail($dto->email);
        $this->userValidator->validateUserCredentials($user, $dto->password);
        $this->userValidator->validateUserIsActive($user);

        $this->tokenManager->createAccessToken($user, 'personal');

        return $user;
    }
}