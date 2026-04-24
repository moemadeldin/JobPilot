<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Interfaces\Auth\TokenManagerInterface;
use App\Interfaces\Auth\UserValidatorInterface;
use App\Models\User;
use App\Utilities\Constants;

final readonly class LoginAction
{
    public function __construct(
        private TokenManagerInterface $tokenManager,
        private UserValidatorInterface $userValidator,
    ) {}

    public function handle(LoginDTO $dto): User
    {
        $user = User::whereEmail($dto->email)->first();

        $this->userValidator->validateUser($user);
        $this->userValidator->validateUserCredentials($user, $dto->password);
        $this->userValidator->validateUserIsActive($user);

        $this->tokenManager->createAccessToken($user, Constants::LOGIN_TOKEN_TYPE);

        return $user;
    }
}
