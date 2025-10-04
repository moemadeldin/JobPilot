<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\JobCategoryDTO;
use App\Interfaces\JobCategoryInterface;
use App\Models\JobCategory;

final class JobCategoryService implements JobCategoryInterface
{
    public function create(JobCategoryDTO $dto): JobCategory
    {
        return JobCategory::create($dto->toArray());
    }

    public function update(JobCategory $category, JobCategoryDTO $dto): JobCategory
    {
        $category->update($dto->toArray());

        return $category;
    }

    public function destroy(JobCategory $category): void
    {
        $category->delete();
    }
}
