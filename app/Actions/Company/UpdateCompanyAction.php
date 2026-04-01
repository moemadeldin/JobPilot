<?php

declare(strict_types=1);

namespace App\Actions\Company;

use App\DTOs\UpdateCompanyDTO;
use App\Models\Company;

final readonly class UpdateCompanyAction
{
    public function handle(UpdateCompanyDTO $dto, Company $company): Company
    {
        $attributes = array_filter(
            $dto->toArray(),
            fn (mixed $value): bool => $value !== null
        );
        $company->update($attributes);

        return $company;
    }
}
