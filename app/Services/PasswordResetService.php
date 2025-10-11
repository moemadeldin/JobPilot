<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Messages\Auth\ValidateMessages;
use App\Events\PasswordVerificationCodeSent;
use App\Exceptions\AuthException;
use App\Interfaces\Auth\PasswordResetInterface;
use App\Interfaces\Auth\TokenManagerInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class PasswordResetService implements PasswordResetInterface
{
    public function __construct(private readonly TokenManagerInterface $tokenManagerService) {}

    public function forgot(string $email): ?User
    {
        return DB::transaction(function () use ($email): ?User {
            $user = User::getUserByEmail($email)->first();

            if (! $user->isActive()) {
                throw new AuthException(ValidateMessages::USER_IS_NOT_ACTIVE->value, Response::HTTP_FORBIDDEN);
            }
            $user->update([
                'verification_code' => $this->generateRandomVerificationCode(),
                'verification_code_expire_at' => Carbon::now()->addMinutes(User::EXPIRATION_VERIFICATION_CODE_TIME_IN_MINUTES),
            ]);
            $user->access_token = $this->tokenManagerService->createAccessToken($user, 'reset');
            event(new PasswordVerificationCodeSent($user->email, $user->verification_code));

            return $user;
        });
    }

    public function checkCode(string $email, string $code): ?User
    {
        return DB::transaction(function () use ($email, $code): ?User {
            $user = User::getUserByEmail($email)->first();

            if (! $user->verification_code = $code) {
                throw new AuthException(ValidateMessages::INCORRECT_CODE->value, Response::HTTP_BAD_REQUEST);
            }
            $user->access_token = $this->tokenManagerService->createAccessToken($user, 'reset');

            return $user;
        });

    }

    public function reset(User $user, string $password): ?User
    {
        $user->update([
            'password' => $password,
        ]);
        $this->tokenManagerService->deleteAccessToken($user);

        return $user;
    }

    private function generateRandomVerificationCode(): int
    {
        return random_int(User::MIN_VERIFICATION_CODE, User::MAX_VERIFICATION_CODE);
    }
}
