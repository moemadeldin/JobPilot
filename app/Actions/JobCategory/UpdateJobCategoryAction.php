<?php

declare(strict_types=1);

namespace App\Actions\JobCategory;

use App\DTOs\JobCategoryDTO;
use App\Models\JobCategory;

final readonly class UpdateJobCategoryAction
{
    public function handle(JobCategory $category, JobCategoryDTO $dto): JobCategory
    {
        $category->update($dto->toArray());

        return $category;
    }
}
