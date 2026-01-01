<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Auth;

use App\Actions\Auth\RegisterAction;
use App\DTOs\Auth\RegisterDTO;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Traits\APIResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class RegisterController
{
    use APIResponses;

    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreUserRequest $request, RegisterAction $action): JsonResponse
    {
        return $this->success(
            $action->handle(
                RegisterDTO::fromArray($request->validated())), SuccessMessages::REGISTERED->value, Response::HTTP_CREATED);
    }
}
