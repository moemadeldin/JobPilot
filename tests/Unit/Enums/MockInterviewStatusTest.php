<?php

declare(strict_types=1);

use App\Enums\MockInterviewStatus;

test('tests all mock interview status enum cases', function (): void {
    expect(MockInterviewStatus::cases())->toHaveCount(2);

    expect(MockInterviewStatus::QUALIFIED->value)->toBe('qualified');
    expect(MockInterviewStatus::DISQUALIFIED->value)->toBe('disqualified');

    expect(MockInterviewStatus::QUALIFIED->label())->toBe('Qualified');
    expect(MockInterviewStatus::DISQUALIFIED->label())->toBe('Disqualified');
});