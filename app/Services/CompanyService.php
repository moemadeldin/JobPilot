<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\CompanyCreateDTO;
use App\DTOs\CompanyUpdateDTO;
use App\Interfaces\CompanyServiceInterface;
use App\Models\Company;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final class CompanyService implements CompanyServiceInterface
{
    public function create(#[CurrentUser] User $user, CompanyCreateDTO $dto): Company
    {
        $company = $user->companies()->create($dto->toArray());

        return $company;
    }

    public function update(CompanyUpdateDTO $dto, Company $company): Company
    {
        $company->update($dto->toArray());

        return $company;
    }
}
