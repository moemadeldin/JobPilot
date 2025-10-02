<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\API\V1\BaseCompanyController;
use App\Interfaces\CompanyServiceInterface;
use App\Models\Company;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class CompanyController extends BaseCompanyController
{
    use APIResponses;

    public function __construct(private readonly CompanyServiceInterface $companyService)
    {
        parent::__construct($companyService);
    }

    protected function getCompanies(#[CurrentUser] User $user): LengthAwarePaginator
    {
        return Company::query()
            ->companiesByOwner($user)
            ->paginate(Company::NUMBER_OF_COMPANIES);
    }
}
