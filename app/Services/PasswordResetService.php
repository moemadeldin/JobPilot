<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\PasswordVerificationCodeSent;
use App\Interfaces\Auth\PasswordResetInterface;
use App\Interfaces\Auth\TokenManagerInterface;
use App\Interfaces\Auth\UserValidatorInterface;
use App\Models\User;
use App\Utilities\Constants;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use SensitiveParameter;

final readonly class PasswordResetService implements PasswordResetInterface
{
    public function __construct(private TokenManagerInterface $tokenManagerService, private UserValidatorInterface $userValidator) {}

    public function forgot(string $email): User
    {
        return DB::transaction(function () use ($email): User {
            $user = User::getUserByEmail($email)->first();

            $this->validateStatusAndUpdateUserWithCodeAndToken($user);

            return $user;
        });
    }

    public function checkCode(string $email, string $verificationCode): User
    {
        return DB::transaction(function () use ($email, $verificationCode): User {
            $user = User::getUserByEmail($email)->first();

            $this->validateCodeAndUpdateUserWithToken($user, $verificationCode);

            return $user;
        });

    }

    public function reset(User $user, #[SensitiveParameter] string $newPassword): User
    {
        $user->update([
            'verification_code' => null,
            'verification_code_expire_at' => null,
            'password' => $newPassword,
        ]);
        $this->tokenManagerService->deleteAccessToken($user);

        return $user;
    }

    private function validateStatusAndUpdateUserWithCodeAndToken(User $user): void
    {
        $this->userValidator->validateUserIsActive($user);

        $user->update([
            'verification_code' => $this->generateRandomVerificationCode(),
            'verification_code_expire_at' => Date::now()->addMinutes(Constants::EXPIRATION_VERIFICATION_CODE_TIME_IN_MINUTES),
        ]);
        $this->tokenManagerService->createAccessToken($user, Constants::PASSWORD_RESET_TOKEN_TYPE);
        event(new PasswordVerificationCodeSent($user->email, $user->verification_code));
    }

    private function validateCodeAndUpdateUserWithToken(User $user, string $verificationCode): void
    {
        $this->userValidator->validateVerificationCode($user, $verificationCode);

        $this->tokenManagerService->createAccessToken($user, Constants::PASSWORD_RESET_TOKEN_TYPE);
    }

    private function generateRandomVerificationCode(): int
    {
        return random_int(Constants::MIN_VERIFICATION_CODE, Constants::MAX_VERIFICATION_CODE);
    }
}
