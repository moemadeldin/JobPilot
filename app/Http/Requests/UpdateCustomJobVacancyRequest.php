<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\EmploymentType;
use App\Enums\Status;
use App\Models\CustomJobVacancy;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateCustomJobVacancyRequest extends FormRequest
{
    public function authorize(
        #[CurrentUser()] User $user,
        #[RouteParameter('customJobVacancy')] CustomJobVacancy $customJobVacancy
    ): bool {
        return $user->is($customJobVacancy->user);
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string'],
            'job_category_id' => ['nullable', 'string', 'exists:job_categories,id'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'expected_salary' => ['nullable', 'string'],
            'employment_type' => ['nullable', Rule::enum(EmploymentType::class)],
            'status' => ['nullable', Rule::enum(Status::class)],
            'responsibilities' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'skills_required' => ['nullable', 'string'],
            'experience_years_min' => ['nullable', 'integer'],
            'experience_years_max' => ['nullable', 'integer'],
            'nice_to_have' => ['nullable', 'string'],
        ];
    }
}
