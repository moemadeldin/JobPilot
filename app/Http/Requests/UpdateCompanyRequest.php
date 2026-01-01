<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Status;
use App\Models\Company;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateCompanyRequest extends FormRequest
{
    public function authorize(
        #[CurrentUser()] User $user,
        #[RouteParameter('company')] Company $company
    ): bool {
        if ($user->isAdmin()) {
            return true;
        }

        return $company->owner()->is($user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'industry' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'website' => ['nullable', 'string'],
            'status' => ['nullable', Rule::enum(Status::class)],
        ];
    }
}
