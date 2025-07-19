<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Auth;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RegisterAction;
use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Exceptions\AuthException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Utils\APIResponses;
use Illuminate\Http\JsonResponse;

final class AuthController extends Controller
{
    use APIResponses;

    public function __construct(
        private readonly RegisterAction $register,
        private readonly LoginAction $login
    ) {}

    public function register(StoreUserRequest $request): JsonResponse
    {
        return $this->success(
            $this->register->handle(
                RegisterDTO::fromArray($request->validated())), SuccessMessages::REGISTERED->value);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            return $this->success(
                $this->login->handle(
                    LoginDTO::fromArray($request->validated())
                ),
                SuccessMessages::LOGGED_IN->value
            );
        } catch (AuthException $e) {
            return $this->fail($e->getMessage(), $e->getCode());
        }
    }

    // public function logout(): Response
    // {
    //     $this->authService->logout(auth()->user());

    //     return $this->noContent();
    // }
}
