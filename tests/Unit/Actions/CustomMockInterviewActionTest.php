<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\CustomMockInterviewAction;

test('action instantiates', function (): void {
    expect(resolve(CustomMockInterviewAction::class))->not->toBeNull();
});
