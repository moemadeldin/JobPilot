<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\CreateResumeAction;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Requests\StoreResumeRequest;
use App\Http\Resources\ResumeResource;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class ResumeController
{
    use APIResponses;

    public function index(#[CurrentUser] User $user): JsonResponse
    {
        return $this->success(new ResumeResource($user->resume), 'Your resume fetched successfully');
    }

    public function store(#[CurrentUser] User $user, StoreResumeRequest $request, CreateResumeAction $action): JsonResponse
    {
        return $this->success($action->handle($request->validated(), $user), SuccessMessages::RESUME_UPLOADED->value, Response::HTTP_CREATED);
    }
}
