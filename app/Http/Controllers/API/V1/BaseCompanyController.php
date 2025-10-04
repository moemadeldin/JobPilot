<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\DTOs\CreateCompanyDTO;
use App\DTOs\UpdateCompanyDTO;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteCompanyRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Interfaces\CompanyServiceInterface;
use App\Models\Company;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class BaseCompanyController extends Controller
{
    use APIResponses;

    public function __construct(private readonly CompanyServiceInterface $companyService) {}

    final public function store(StoreCompanyRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        return $this->success(
            CompanyResource::make($this->companyService->create($user, CreateCompanyDTO::fromArray($request->validated()))), SuccessMessages::COMPANY_CREATED->value, Response::HTTP_CREATED
        );
    }

    final public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        return $this->success(
            CompanyResource::make($this->companyService->update(UpdateCompanyDTO::fromArray($request->validated()), $company)), SuccessMessages::COMPANY_UPDATED->value
        );
    }

    final public function destroy(DeleteCompanyRequest $request, Company $company): Response
    {
        $this->companyService->delete($company);

        return $this->noContent();
    }
}
