<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;

final class DeleteCompanyRequest extends FormRequest
{
    public function authorize(
        #[CurrentUser] User $user,
        #[RouteParameter('company')] Company $company
    ): bool {
        if ($user->isAdmin()) {
            return true;
        }

        return $company->owner()->is($user);
    }
}
