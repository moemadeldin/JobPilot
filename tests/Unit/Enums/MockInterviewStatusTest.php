<?php

declare(strict_types=1);

use App\Enums\MockInterviewStatus;

test('tests all mock interview status enum cases', function (): void {
    expect(MockInterviewStatus::cases())->toHaveCount(4);
    
    expect(MockInterviewStatus::SUGGESTED->value)->toBe('suggested');
    expect(MockInterviewStatus::ACCEPTED->value)->toBe('accepted');
    expect(MockInterviewStatus::DECLINED->value)->toBe('declined');
    expect(MockInterviewStatus::DISQUALIFIED->value)->toBe('disqualified');
    
    expect(MockInterviewStatus::SUGGESTED->label())->toBe('Suggested');
    expect(MockInterviewStatus::ACCEPTED->label())->toBe('Accepted');
    expect(MockInterviewStatus::DECLINED->label())->toBe('Declined');
    expect(MockInterviewStatus::DISQUALIFIED->label())->toBe('Disqualified');
});
