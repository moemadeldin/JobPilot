<?php

declare(strict_types=1);

namespace App\Actions\CustomJobVacancy;

use App\DTOs\CreateCustomJobVacancyDTO;
use App\Models\CustomJobVacancy;
use App\Models\User;

final readonly class CreateCustomJobVacancyAction
{
    public function handle(CreateCustomJobVacancyDTO $dto, User $user): CustomJobVacancy
    {
        return CustomJobVacancy::query()->create([
            ...$dto->toArray(),
            'user_id' => $user->id,
        ]);
    }
}
