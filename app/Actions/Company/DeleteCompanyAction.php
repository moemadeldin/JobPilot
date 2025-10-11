<?php

declare(strict_types=1);

namespace App\Actions\Company;

use App\Models\Company;

final readonly class DeleteCompanyAction
{
    public function handle(Company $company): void
    {
        $company->delete();
    }
}
