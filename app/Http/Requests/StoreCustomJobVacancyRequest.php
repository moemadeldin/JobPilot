<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\EmploymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreCustomJobVacancyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'category' => ['nullable', 'string'],
            'company' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'employment_type' => ['nullable', Rule::enum(EmploymentType::class)],
            'responsibilities' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'skills_required' => ['nullable', 'string'],
            'experience_years_min' => ['nullable', 'integer'],
            'experience_years_max' => ['nullable', 'integer'],
            'nice_to_have' => ['nullable', 'string'],
        ];
    }
}
