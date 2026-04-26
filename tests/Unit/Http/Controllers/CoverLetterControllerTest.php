<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\API\V1\CoverLetterController;

test('controller instantiates', function (): void {
    expect(resolve(CoverLetterController::class))->not->toBeNull();
});
