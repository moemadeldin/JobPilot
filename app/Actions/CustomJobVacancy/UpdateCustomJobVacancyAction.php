<?php

declare(strict_types=1);

namespace App\Actions\CustomJobVacancy;

use App\DTOs\UpdateCustomJobVacancyDTO;
use App\Models\CustomJobVacancy;

final readonly class UpdateCustomJobVacancyAction
{
    public function handle(UpdateCustomJobVacancyDTO $dto, CustomJobVacancy $customJobVacancy): CustomJobVacancy
    {
        $customJobVacancy->update($dto->toArray());

        return $customJobVacancy;
    }
}
