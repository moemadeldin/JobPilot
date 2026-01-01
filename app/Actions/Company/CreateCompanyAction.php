<?php

declare(strict_types=1);

namespace App\Actions\Company;

use App\DTOs\CreateCompanyDTO;
use App\Models\Company;
use App\Models\User;

final readonly class CreateCompanyAction
{
    public function handle(User $user, CreateCompanyDTO $dto): Company
    {
        return $user->companies()->create($dto->toArray());
    }
}
