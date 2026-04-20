<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\EmploymentType;
use App\Enums\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class JobVacancyFilterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => ['nullable', 'string', 'exists:job_categories,slug'],
            'type' => ['nullable', 'string', Rule::enum(EmploymentType::class)],
            'status' => ['nullable', 'string', Rule::enum(Status::class)],
            'location' => ['nullable', 'string'],
        ];
    }
}
