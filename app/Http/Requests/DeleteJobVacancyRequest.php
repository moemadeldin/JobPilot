<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;

final class DeleteJobVacancyRequest extends FormRequest
{
    public function authorize(
        #[CurrentUser] User $user,
        #[RouteParameter('job_vacancy')] JobVacancy $jobVacancy
    ): bool {
        return $user->isAdmin() || $jobVacancy->company->owner->is($user);
    }
}
