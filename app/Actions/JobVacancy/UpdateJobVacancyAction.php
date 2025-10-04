<?php

declare(strict_types=1);

namespace App\Actions\JobVacancy;

use App\DTOs\UpdateJobVacancyDTO;
use App\Models\JobVacancy;

final readonly class UpdateJobVacancyAction
{
    public function handle(UpdateJobVacancyDTO $dto, JobVacancy $jobVacancy): JobVacancy
    {
        $jobVacancy->update(
            array_filter(
                $dto->toArray(),
                fn (?string $value): bool => $value !== null
            )
        );

        return $jobVacancy;
    }
}
