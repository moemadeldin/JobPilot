<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\CustomJobApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final readonly class UserCustomApplicationQuery
{
    /**
     * @return Builder<CustomJobApplication>
     */
    public function builder(array $data, User $user): Builder
    {
        $status = $data['status'] ?? null;

        return CustomJobApplication::query()
            ->where('user_id', $user->id)
            ->with(['customJobVacancy', 'mockInterview'])
            ->filterStatus(is_string($status) ? $status : null)
            ->latest();
    }
}
