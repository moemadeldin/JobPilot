<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateCompanyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'industry' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'website' => ['nullable', 'string'],
            'is_active' => ['nullable', Rule::in(Status::cases())],
        ];
    }
}
