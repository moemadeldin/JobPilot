<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\MockInterviewResource;
use App\Models\MockInterview;

test('resource instantiates', function (): void {
    $mockInterview = MockInterview::factory()->create();
    expect(new MockInterviewResource($mockInterview))->not->toBeNull();
});
