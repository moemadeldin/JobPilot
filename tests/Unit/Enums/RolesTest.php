<?php

declare(strict_types=1);

use App\Enums\Roles;

test('tests all roles enum cases', function (): void {
    expect(Roles::ADMIN->value)->toBe('admin');
    expect(Roles::OWNER->value)->toBe('owner');
    expect(Roles::USER->value)->toBe('user');

    expect(Roles::ADMIN->label())->toBe('Admin');
    expect(Roles::OWNER->label())->toBe('Owner');
    expect(Roles::USER->label())->toBe('User');
});
