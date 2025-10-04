<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\V1\BaseCompanyController;
use App\Http\Resources\CompanyResource;
use App\Interfaces\CompanyServiceInterface;
use App\Models\Company;
use App\Traits\APIResponses;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class CompanyController extends BaseCompanyController
{
    use APIResponses;

    public function __construct(private readonly CompanyServiceInterface $companyService)
    {
        parent::__construct($companyService);
    }

    final public function index(): AnonymousResourceCollection
    {
        return CompanyResource::collection(Company::query()
            ->companies()
            ->paginate(Company::NUMBER_OF_COMPANIES));
    }
}
