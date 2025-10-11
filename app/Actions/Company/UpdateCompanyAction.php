<?php

declare(strict_types=1);

namespace App\Actions\Company;

use App\DTOs\UpdateCompanyDTO;
use App\Models\Company;

final readonly class UpdateCompanyAction
{
    public function handle(UpdateCompanyDTO $dto, Company $company): Company
    {
        $company->update(
            array_filter(
                $dto->toArray(),
                fn (?string $value): bool => $value !== null
            )
        );

        return $company;
    }
}
