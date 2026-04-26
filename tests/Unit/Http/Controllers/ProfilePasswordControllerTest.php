<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\API\V1\ProfilePasswordController;

test('controller instantiates', function (): void {
    expect(resolve(ProfilePasswordController::class))->not->toBeNull();
});
