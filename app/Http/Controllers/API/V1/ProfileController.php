<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\CreateAvatarAction;
use App\Actions\DeleteAccountAction;
use App\Exceptions\AuthException;
use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\StoreAvatarRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

final readonly class ProfileController
{
    use APIResponses;

    public function store(
        StoreAvatarRequest $request,
        CreateAvatarAction $action,
        #[CurrentUser] User $user,
    ): JsonResponse {
        /** @var UploadedFile $avatar */
        $avatar = $request->file('avatar');

        $user = $action->handle($user, $avatar);

        return $this->success(new ProfileResource($user), 'Avatar uploaded successfully.');
    }

    public function destroy(
        DeleteAccountRequest $request,
        DeleteAccountAction $action,
        #[CurrentUser] User $user,
    ): Response|JsonResponse {
        try {
            /** @var array<string, mixed> $data */
            $data = $request->validated();

            $action->handle($user, (string) $data['password']);

            return $this->noContent();
        } catch (AuthException $authException) {
            return $this->fail($authException->getMessage(), $authException->getCode());
        }
    }
}
