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
use App\Http\Resources\LoginResource;
use App\Http\Resources\ProfileResource;
use App\Interfaces\Auth\TokenManagerInterface;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class AuthController extends Controller
{
    use APIResponses;

    public function __construct(private readonly TokenManagerInterface $tokenManager) {}

    public function register(StoreUserRequest $request, RegisterAction $action): JsonResponse
    {
        return $this->success(
            $action->handle(
                RegisterDTO::fromArray($request->validated())), SuccessMessages::REGISTERED->value, Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        try {
            return $this->success(
                new LoginResource(
                    $action->handle(
                        LoginDTO::fromArray($request->validated())
                    ),
                ),
                SuccessMessages::LOGGED_IN->value
            );
        } catch (AuthException $e) {
            return $this->fail($e->getMessage(), $e->getCode());
        }
    }

    public function logout(#[CurrentUser] User $user): Response
    {
        $this->tokenManager->deleteAccessToken($user);

        return $this->noContent();
    }

    public function me(#[CurrentUser] User $user): JsonResponse
    {
        return $this->success(new ProfileResource($user), 'me');
    }
}
