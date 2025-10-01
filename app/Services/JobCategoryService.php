<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\JobCategoryDTO;
use App\Models\JobCategory;

final class JobCategoryService
{
    public function create(JobCategoryDTO $dto): JobCategory
    {
        return JobCategory::create($dto->toArray());
    }
    public function update(JobCategory $jobCategory, JobCategoryDTO $dto): JobCategory
    {
        $jobCategory->update($dto->toArray());

        return $jobCategory;
    }
}