<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Auth;

use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerificationCodeRequest;
use App\Interfaces\Auth\PasswordResetInterface;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;

final class PasswordResetController extends Controller
{
    use APIResponses;

    public function __construct(private readonly PasswordResetInterface $passwordResetService) {}

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        return $this->success(
            $this->passwordResetService->forgot($request->safe()->email), SuccessMessages::CODE_SENT->value
        );
    }

    public function checkCode(VerificationCodeRequest $request): JsonResponse
    {
        return $this->success(
            $this->passwordResetService->checkCode($request->safe()->email, $request->safe()->code), SuccessMessages::CODE_IS_CORRECT->value
        );
    }

    public function resetPassword(#[CurrentUser] User $user, ResetPasswordRequest $request): JsonResponse
    {
        return $this->success(
            $this->passwordResetService->reset($user, $request->safe()->password), SuccessMessages::PASSWORD_RECOVERED->value
        );
    }
}
