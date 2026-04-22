<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\CustomJobApplication;
use Illuminate\Database\Eloquent\Builder;

final readonly class UserCustomApplicationQuery
{
    /**
     * @return Builder<CustomJobApplication>
     */
    public function builder(array $data): Builder
    {
        return CustomJobApplication::query()
            ->with(['customJobVacancy'])
            ->filterStatus($data['status'] ?? null);
    }
}
