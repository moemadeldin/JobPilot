<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\API\V1\BaseCompanyController;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\User;
use App\Traits\APIResponses;
use App\Utilities\Constants;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class CompanyController extends BaseCompanyController
{
    use APIResponses;

    final public function index(#[CurrentUser] User $user): AnonymousResourceCollection
    {
        return CompanyResource::collection(Company::query()
            ->companiesByOwner($user)
            ->paginate(Constants::NUMBER_OF_COMPANIES));
    }
}
