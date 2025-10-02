<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Interfaces\Auth\TokenManagerInterface;
use App\Interfaces\Auth\UserValidatorInterface;
use App\Models\User;

final class LoginAction
{
    public function __construct(
        private readonly TokenManagerInterface $tokenManager,
        private readonly UserValidatorInterface $userValidator,
    ) {}

    public function handle(LoginDTO $dto): User
    {
        $user = User::getUserByEmail($dto->email)->first();
        $this->userValidator->validateUserCredentials($user, $dto->password);
        $this->userValidator->validateUserIsActive($user);

        $this->tokenManager->createAccessToken($user, 'login');

        return $user;
    }
}
