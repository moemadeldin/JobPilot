<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\DTOs\JobCategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobCategoryRequest;
use App\Http\Requests\UpdateJobCategoryRequest;
use App\Models\JobCategory;
use App\Services\JobCategoryService;
use App\Utils\APIResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class CategoryController extends Controller
{
    use APIResponses;

    public function __construct(private readonly JobCategoryService $jobCategoryService) {}

    public function index() {}

    public function store(StoreJobCategoryRequest $request): JsonResponse
    {

        return $this->success($this->jobCategoryService->create(JobCategoryDTO::fromArray($request->validated())), 'job category stored successfully.');
    }

    public function update(UpdateJobCategoryRequest $request, JobCategory $jobCategory): JsonResponse
    {
        return $this->success($this->jobCategoryService->update($jobCategory, JobCategoryDTO::fromArray($request->validated())), 'job category updated successfully.');
    }

    public function destroy(JobCategory $jobCategory): Response
    {
        $this->authorize('delete');
        $jobCategory->delete();

        return $this->noContent();
    }

    public function restore(JobCategory $jobCategory): Response
    {
        $this->authorize('restore');
        $jobCategory->restore();

        return $this->noContent();
    }
}
