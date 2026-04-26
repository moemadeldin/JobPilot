<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\DeclineMockInterviewAction;
use App\Enums\MockInterviewStatus;
use App\Models\CustomJobApplication;
use App\Models\MockInterview;
use Exception;

test('it declines mock interview', function (): void {
    $application = CustomJobApplication::factory()->create();
    $mockInterview = MockInterview::factory()->create([
        'application_id' => $application->id,
        'status' => MockInterviewStatus::QUALIFIED,
    ]);

    $action = new DeclineMockInterviewAction();
    $result = $action->handle($application);

    expect($result->id)->toBe($application->id);
    expect($mockInterview->fresh()->status->value)->toBe(MockInterviewStatus::DISQUALIFIED->value);
});

test('it throws exception when mock interview not found', function (): void {
    $application = CustomJobApplication::factory()->create();

    $action = new DeclineMockInterviewAction();

    expect(fn (): CustomJobApplication => $action->handle($application))->toThrow(Exception::class, 'Mock interview not found');
});
