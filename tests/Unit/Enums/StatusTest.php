<?php

declare(strict_types=1);

use App\Enums\Status;

test('tests all status enum cases', function (): void {
    expect(Status::ACTIVE->value)->toBe('active');
    expect(Status::INACTIVE->value)->toBe('inactive');
    expect(Status::BLOCKED->value)->toBe('blocked');

    expect(Status::ACTIVE->label())->toBe('Active');
    expect(Status::INACTIVE->label())->toBe('Inactive');
    expect(Status::BLOCKED->label())->toBe('Blocked');
});
