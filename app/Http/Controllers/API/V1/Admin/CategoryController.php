<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\JobCategory\CreateJobCategoryAction;
use App\Actions\JobCategory\DeleteJobCategoryAction;
use App\Actions\JobCategory\UpdateJobCategoryAction;
use App\DTOs\JobCategoryDTO;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Requests\StoreJobCategoryRequest;
use App\Http\Requests\UpdateJobCategoryRequest;
use App\Models\JobCategory;
use App\Traits\APIResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class CategoryController
{
    use APIResponses;

    public function store(StoreJobCategoryRequest $request, CreateJobCategoryAction $action): JsonResponse
    {
        return $this->success($action->handle(JobCategoryDTO::fromArray($request->validated())), SuccessMessages::JOB_CATEGORY_CREATED->value, Response::HTTP_CREATED);
    }

    public function update(UpdateJobCategoryRequest $request, JobCategory $category, UpdateJobCategoryAction $action): JsonResponse
    {
        return $this->success($action->handle($category, JobCategoryDTO::fromArray($request->validated())), SuccessMessages::JOB_CATEGORY_UPDATED->value);
    }

    public function destroy(JobCategory $category, DeleteJobCategoryAction $action): Response
    {
        $action->handle($category);

        return $this->noContent();
    }
}
