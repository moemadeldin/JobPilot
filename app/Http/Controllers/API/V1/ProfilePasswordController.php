<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\ChangePasswordAction;
use App\DTOs\ChangePasswordDTO;
use App\Exceptions\AuthException;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;

final readonly class ProfilePasswordController
{
    use APIResponses;

    public function __invoke(
        ChangePasswordRequest $request,
        ChangePasswordAction $action,
        #[CurrentUser] User $user,
    ): JsonResponse {
        try {
            /** @var array<string, mixed> $data */
            $data = $request->validated();

            $action->handle($user, ChangePasswordDTO::fromArray($data));

            return $this->success(new ProfileResource($user), 'Password changed successfully.');
        } catch (AuthException $authException) {
            return $this->fail($authException->getMessage(), $authException->getCode());
        }
    }
}
