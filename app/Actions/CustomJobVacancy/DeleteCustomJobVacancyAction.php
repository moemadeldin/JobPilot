<?php

declare(strict_types=1);

namespace App\Actions\CustomJobVacancy;

use App\Models\CustomJobVacancy;

final readonly class DeleteCustomJobVacancyAction
{
    public function handle(CustomJobVacancy $customJobVacancy): void
    {
        $customJobVacancy->delete();
    }
}
