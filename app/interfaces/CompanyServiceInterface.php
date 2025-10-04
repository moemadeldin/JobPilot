<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\DTOs\CreateCompanyDTO;
use App\DTOs\UpdateCompanyDTO;
use App\Models\Company;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

interface CompanyServiceInterface
{
    public function create(#[CurrentUser] User $user, CreateCompanyDTO $dto): Company;

    public function update(UpdateCompanyDTO $dto, Company $company): Company;

    public function delete(Company $company): void;
}
