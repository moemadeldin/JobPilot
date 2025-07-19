<?php

namespace App\Http\Requests;

use App\Enums\Status;
use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Company::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'industry' => ['required', 'string'],
            'address' => ['required', 'string'],
            'website' => ['nullable', 'string'],
            'is_active' => ['required', Rule::in(Status::cases())],
        ];
    }
}
