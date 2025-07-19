<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\CompanyCreateDTO;
use App\DTOs\CompanyUpdateDTO;
use App\Models\Company;

final class CompanyService 
{
    public function create(CompanyCreateDTO $dto): Company
    {
        $company = auth()->user()->companies()->create($dto->toArray());
        return $company;
    }
    public function update(CompanyUpdateDTO $dto, Company $company): Company
    {
        $company->update($dto->toArray());
        return $company;
    }
}