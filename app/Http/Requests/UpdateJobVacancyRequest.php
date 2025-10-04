<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\EmploymentType;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateJobVacancyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string'],
            'job_category_id' => ['nullable', 'string', 'exists:job_categories,id'],
            'company_id' => ['nullable', 'string', 'exists:companies,id'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'expected_salary' => ['nullable', 'numeric'],
            'employment_type' => ['nullable', Rule::in(EmploymentType::cases())],
            'is_active' => ['nullable', Rule::in(Status::cases())],
        ];
    }
}
