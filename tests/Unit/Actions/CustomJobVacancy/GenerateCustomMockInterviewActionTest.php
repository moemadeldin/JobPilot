<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\CustomJobVacancy\GenerateCustomMockInterviewAction;

test('action instantiates', function (): void {
    expect(resolve(GenerateCustomMockInterviewAction::class))->not->toBeNull();
});
