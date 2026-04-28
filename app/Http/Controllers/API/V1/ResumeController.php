<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\CreateResumeAction;
use App\Http\Requests\StoreResumeRequest;
use App\Http\Resources\ResumeResource;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

final readonly class ResumeController
{
    use APIResponses;

    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $user->loadMissing('resume');

        return $this->success(new ResumeResource($user->resume), 'Your resume fetched successfully');
    }

    public function store(#[CurrentUser] User $user, StoreResumeRequest $request, CreateResumeAction $action): JsonResponse
    {
        /** @var array{path: string|UploadedFile} $data */
        $data = $request->validated();

        return $this->success(new ResumeResource($action->handle($data, $user)), 'Resume has been uploaded.', Response::HTTP_CREATED);
    }
}
