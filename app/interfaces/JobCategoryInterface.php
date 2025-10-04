<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\DTOs\JobCategoryDTO;
use App\Models\JobCategory;

interface JobCategoryInterface
{
    public function create(JobCategoryDTO $dto): JobCategory;

    public function update(JobCategory $category, JobCategoryDTO $dto): JobCategory;

    public function destroy(JobCategory $category): void;
}
