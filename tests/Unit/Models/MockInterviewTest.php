<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\MockInterview;
use App\Models\MockInterviewQuestion;

test('factory', function (): void {
    expect(MockInterview::factory()->create())->not->toBeNull();
});

test('questions relationship', function (): void {
    $mockInterview = MockInterview::factory()->create();
    MockInterviewQuestion::factory()->count(3)->create(['mock_interview_id' => $mockInterview->id]);

    expect($mockInterview->questions)->toHaveCount(3);
});
