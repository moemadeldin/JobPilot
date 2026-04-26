<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\CustomJobVacancy\CreateCustomJobApplicationAction;

test('action instantiates', function (): void {
    expect(resolve(CreateCustomJobApplicationAction::class))->not->toBeNull();
});
