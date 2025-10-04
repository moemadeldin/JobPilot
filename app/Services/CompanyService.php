<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\CreateCompanyDTO;
use App\DTOs\UpdateCompanyDTO;
use App\Interfaces\CompanyServiceInterface;
use App\Models\Company;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final class CompanyService implements CompanyServiceInterface
{
    public function create(#[CurrentUser] User $user, CreateCompanyDTO $dto): Company
    {
        $company = $user->companies()->create($dto->toArray());

        return $company;
    }

    public function update(UpdateCompanyDTO $dto, Company $company): Company
    {
        $company->update(
            array_filter(
                $dto->toArray(),
                fn (?string $value): bool => $value !== null
            )
        );

        return $company;
    }

    public function delete(Company $company): void
    {
        $company->delete();
    }
}
