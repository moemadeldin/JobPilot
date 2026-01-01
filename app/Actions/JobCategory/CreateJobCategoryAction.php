<?php

declare(strict_types=1);

namespace App\Actions\JobCategory;

use App\DTOs\JobCategoryDTO;
use App\Models\JobCategory;

final readonly class CreateJobCategoryAction
{
    public function handle(JobCategoryDTO $dto): JobCategory
    {
        return JobCategory::query()->create($dto->toArray());
    }
}
