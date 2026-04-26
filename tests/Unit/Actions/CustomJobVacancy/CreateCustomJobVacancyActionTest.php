<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\CustomJobVacancy\CreateCustomJobVacancyAction;

test('action instantiates', function (): void {
    expect(resolve(CreateCustomJobVacancyAction::class))->not->toBeNull();
});
