<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\V1\BaseCompanyController;
use App\Interfaces\CompanyServiceInterface;
use App\Models\Company;
use App\Traits\APIResponses;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class CompanyController extends BaseCompanyController
{
    use APIResponses;

    public function __construct(private readonly CompanyServiceInterface $companyService)
    {
        parent::__construct($companyService);
    }

    protected function getCompanies(): LengthAwarePaginator
    {
        return Company::query()
            ->companies()
            ->paginate(Company::NUMBER_OF_COMPANIES);
    }
}
