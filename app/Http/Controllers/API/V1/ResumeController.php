<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\CreateResumeAction;
use App\DTOs\CreateResumeDTO;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Requests\StoreResumeRequest;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class ResumeController
{
    use APIResponses;

    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, StoreResumeRequest $request, CreateResumeAction $action): JsonResponse
    {
        return $this->success($action->handle($user, CreateResumeDTO::fromArray($request->validated())), SuccessMessages::RESUME_UPLOADED->value, Response::HTTP_CREATED);
    }
}
