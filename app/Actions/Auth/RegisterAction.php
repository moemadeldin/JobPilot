<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\Auth\RegisterDTO;
use App\Models\User;
use App\Services\TokenManager;
use App\Utilities\Constants;
use Illuminate\Support\Facades\DB;

final readonly class RegisterAction
{
    public function __construct(
        private TokenManager $tokenManager,
    ) {}

    public function handle(RegisterDTO $dto): User
    {
        return DB::transaction(function () use ($dto): User {

            $user = User::query()->create([
                'email' => $dto->email,
                'password' => $dto->password,
            ]);

            $user->profile()->create([
                'avatar' => Constants::DEFAULT_PROFILE_PICTURE_PATH,
            ]);

            $this->tokenManager->createAccessToken($user, Constants::REGISTER_TOKEN_TYPE);

            return $user;
        });
    }
}
