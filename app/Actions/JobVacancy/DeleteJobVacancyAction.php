<?php

declare(strict_types=1);

namespace App\Actions\JobVacancy;

use App\Models\JobVacancy;

final readonly class DeleteJobVacancyAction
{
    public function handle(JobVacancy $jobVacancy): void
    {
        $jobVacancy->delete();
    }
}
