<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\ChangePasswordAction;
use App\DTOs\Auth\ChangePasswordDTO;
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

        /** @var array{current_password: mixed, new_password: mixed} $data */
        $data = $request->validated();

        $action->handle($user, ChangePasswordDTO::fromArray($data));

        return $this->success(new ProfileResource($user), 'Password changed successfully.');

    }
}
