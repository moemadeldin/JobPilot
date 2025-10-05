<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final readonly class FilteredJobVacancyQuery
{
    public function builder(array $data, ?User $user = null): Builder
    {
        $query = JobVacancy::query()
            ->with(['company', 'category'])
            ->filterJobCategory($data['job_category_id'] ?? null)
            ->filterEmploymentType($data['employment_type'] ?? null)
            ->filterStatus($data['is_active'] ?? null)
            ->filterLocation($data['location'] ?? null);

        if ($user && $user->isOwner()) {
            $query->whereHas('company', function (Builder $q) use ($user): void {
                $q->where('user_id', $user->id);
            });
        }

        return $query;
    }
}
