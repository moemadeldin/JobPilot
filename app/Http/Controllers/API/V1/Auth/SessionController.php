<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Auth;

use App\Actions\Auth\LoginAction;
use App\DTOs\Auth\LoginDTO;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Exceptions\AuthException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\ProfileResource;
use App\Interfaces\Auth\TokenManagerInterface;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class SessionController
{
    use APIResponses;

    public function __construct(private TokenManagerInterface $tokenManager) {}

    public function show(#[CurrentUser] User $user): JsonResponse
    {
        return $this->success(new ProfileResource($user), '');
    }

    public function store(LoginRequest $request, LoginAction $action): JsonResponse
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
        } catch (AuthException $authException) {
            return $this->fail($authException->getMessage(), $authException->getCode());
        }
    }

    public function destroy(#[CurrentUser] User $user): Response
    {
        $this->tokenManager->deleteAccessToken($user);

        return $this->noContent();
    }
}
