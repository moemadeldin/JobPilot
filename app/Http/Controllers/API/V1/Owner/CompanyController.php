<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\API\V1\BaseCompanyController;
use App\Repositories\CompanyRepository;
use App\Services\CompanyService;
use App\Utils\APIResponses;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class CompanyController extends BaseCompanyController
{
    use APIResponses;

    public function __construct(private readonly CompanyRepository $companyRepository, private readonly CompanyService $companyService) {
        parent::__construct($companyService);
    }
    protected function getCompanies(): LengthAwarePaginator
    {
        return $this->companyRepository->getAllCompaniesByOwner(auth()->user());
    }
}
