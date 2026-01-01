<?php

declare(strict_types=1);

use App\Enums\EmploymentType;

test('tests all employment types enum cases', function (): void {
    expect(EmploymentType::FULL_TIME->value)->toBe('full-time');
    expect(EmploymentType::REMOTELY->value)->toBe('remotely');
    expect(EmploymentType::PART_TIME->value)->toBe('part-time');
    expect(EmploymentType::HYBRID->value)->toBe('hybrid');

    expect(EmploymentType::FULL_TIME->label())->toBe('Full-Time');
    expect(EmploymentType::REMOTELY->label())->toBe('Remote');
    expect(EmploymentType::PART_TIME->label())->toBe('Part-Time');
    expect(EmploymentType::HYBRID->label())->toBe('Hybrid');
});
