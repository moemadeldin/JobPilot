<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\DTOs\CompanyCreateDTO;
use App\DTOs\CompanyUpdateDTO;
use App\Models\Company;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

interface CompanyServiceInterface
{
    public function create(#[CurrentUser] User $user, CompanyCreateDTO $dto): Company;

    public function update(CompanyUpdateDTO $dto, Company $company): Company;
}
