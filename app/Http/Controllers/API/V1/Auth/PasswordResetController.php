<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerificationCodeRequest;
use App\Interfaces\Auth\PasswordResetInterface;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;

final readonly class PasswordResetController
{
    use APIResponses;

    public function __construct(private PasswordResetInterface $passwordResetService) {}

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        /** @var string $email */
        $email = $request->input('email');

        return $this->success(
            $this->passwordResetService->forgot($email), 'Verification Code Sent Successfully.'
        );
    }

    public function checkCode(VerificationCodeRequest $request): JsonResponse
    {
        /** @var string $email */
        $email = $request->input('email');
        /** @var string $code */
        $code = $request->input('code');

        return $this->success(
            $this->passwordResetService->checkCode($email, $code), 'Verification Code is Correct.'
        );
    }

    public function resetPassword(#[CurrentUser] User $user, ResetPasswordRequest $request): JsonResponse
    {
        /** @var string $newPassword */
        $newPassword = $request->input('new_password');

        return $this->success(
            $this->passwordResetService->reset($user, $newPassword), 'Password has been recovered.'
        );
    }
}
