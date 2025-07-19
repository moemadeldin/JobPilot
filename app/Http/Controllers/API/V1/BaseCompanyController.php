<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\DTOs\CompanyCreateDTO;
use App\DTOs\CompanyUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use App\Utils\APIResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

abstract class BaseCompanyController extends Controller
{
    use APIResponses;

    public function __construct(private readonly CompanyService $companyService) {}

    final public function index(): AnonymousResourceCollection
    {
        return CompanyResource::collection($this->getCompanies());
    }

    final public function store(StoreCompanyRequest $request): JsonResponse
    {
        return $this->success(CompanyResource::make($this->companyService->create(CompanyCreateDTO::fromArray($request->validated()))), 'Company created successfully');
    }

    final public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        return $this->success(CompanyResource::make($this->companyService->update(CompanyUpdateDTO::fromArray($request->validated()), $company)), 'Company updated');
    }

    final public function destroy(Company $company): Response
    {
        $this->authorize('delete', $company);

        $company->delete();

        return $this->noContent();
    }

    final public function restore(Company $company): Response
    {
        $this->authorize('restore', $company);

        $company->restore();

        return $this->noContent();
    }
}
