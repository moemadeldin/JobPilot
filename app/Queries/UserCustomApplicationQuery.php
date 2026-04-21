<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\CustomJobApplication;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final readonly class UserCustomApplicationQuery
{
    /**
     * @param  array<string, mixed>  $filters
     * @return Builder<JobApplication>
     */
    public function builder(array $filters = [], ?User $user = null): Builder
    {
        return CustomJobApplication::query()
            ->where('user_id', $user?->id)
            ->with(['customJobVacancy'])
            ->latest();
    }
}
