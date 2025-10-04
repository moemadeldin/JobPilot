<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\DTOs\JobCategoryDTO;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobCategoryRequest;
use App\Http\Requests\UpdateJobCategoryRequest;
use App\Interfaces\JobCategoryInterface;
use App\Models\JobCategory;
use App\Traits\APIResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class CategoryController extends Controller
{
    use APIResponses;

    public function __construct(private readonly JobCategoryInterface $jobCategoryService) {}

    public function store(StoreJobCategoryRequest $request): JsonResponse
    {

        return $this->success($this->jobCategoryService->create(JobCategoryDTO::fromArray($request->validated())), SuccessMessages::JOB_CATEGORY_CREATED->value, Response::HTTP_CREATED);
    }

    public function update(UpdateJobCategoryRequest $request, JobCategory $category): JsonResponse
    {
        return $this->success($this->jobCategoryService->update($category, JobCategoryDTO::fromArray($request->validated())), SuccessMessages::JOB_CATEGORY_UPDATED->value);
    }

    public function destroy(JobCategory $category): Response
    {
        $this->jobCategoryService->destroy($category);

        return $this->noContent();
    }
}
