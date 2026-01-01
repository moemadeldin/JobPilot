<?php

declare(strict_types=1);

namespace App\Actions\JobVacancy;

use App\DTOs\CreateJobVacancyDTO;
use App\Models\JobVacancy;

final readonly class CreateJobVacancyAction
{
    public function handle(CreateJobVacancyDTO $dto): JobVacancy
    {
        return JobVacancy::query()->create($dto->toArray());
    }
}
