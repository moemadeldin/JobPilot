<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\EmploymentType;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreJobVacancyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
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
            'employment_type' => ['required', Rule::in(EmploymentType::cases())],
            'is_active' => ['required', Rule::in(Status::cases())],
        ];
    }
}
