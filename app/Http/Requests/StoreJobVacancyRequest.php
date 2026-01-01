<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\EmploymentType;
use App\Enums\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreJobVacancyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'job_category_id' => ['required', 'string', 'exists:job_categories,id'],
            'company_id' => ['required', 'string', 'exists:companies,id'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string'],
            'expected_salary' => ['required', 'string'],
            'employment_type' => ['required', Rule::enum(EmploymentType::class)],
            'status' => ['required', Rule::enum(Status::class)],
            'responsibilities' => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'skills_required' => ['required', 'string'],
            'experience_years_min' => ['required', 'integer'],
            'experience_years_max' => ['required', 'integer'],
            'nice_to_have' => ['nullable', 'string'],
        ];
    }
}
