<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreCompanyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'industry' => ['required', 'string'],
            'address' => ['required', 'string'],
            'website' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(Status::class)],
        ];
    }
}
