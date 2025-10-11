<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\Company\CreateCompanyAction;
use App\Actions\Company\DeleteCompanyAction;
use App\Actions\Company\UpdateCompanyAction;
use App\DTOs\CreateCompanyDTO;
use App\DTOs\UpdateCompanyDTO;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteCompanyRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class BaseCompanyController extends Controller
{
    use APIResponses;

    final public function store(StoreCompanyRequest $request, #[CurrentUser] User $user, CreateCompanyAction $action): JsonResponse
    {
        return $this->success(
            CompanyResource::make($action->handle($user, CreateCompanyDTO::fromArray($request->validated()))), SuccessMessages::COMPANY_CREATED->value, Response::HTTP_CREATED
        );
    }

    final public function update(UpdateCompanyRequest $request, Company $company, UpdateCompanyAction $action): JsonResponse
    {
        return $this->success(
            CompanyResource::make($action->handle(UpdateCompanyDTO::fromArray($request->validated()), $company)), SuccessMessages::COMPANY_UPDATED->value
        );
    }

    final public function destroy(DeleteCompanyRequest $request, Company $company, DeleteCompanyAction $action): Response
    {
        $action->handle($company);

        return $this->noContent();
    }
}
