<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Builder;

final readonly class FilteredJobVacancyQuery
{
    public function builder(array $data): Builder
    {
        return JobVacancy::query()
            ->with(['company', 'category'])
            ->filterJobCategory($data['job_category_id'] ?? null)
            ->filterEmploymentType($data['employment_type'] ?? null)
            ->filterStatus($data['is_active'] ?? null)
            ->filterLocation($data['location'] ?? null);
    }
}