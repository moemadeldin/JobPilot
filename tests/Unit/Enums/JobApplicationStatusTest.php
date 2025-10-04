<?php

declare(strict_types=1);

use App\Enums\JobApplicationStatus;

test('tests all job application status enum cases', function (): void {
    expect(JobApplicationStatus::PENDING->value)->toBe('pending');
    expect(JobApplicationStatus::APPROVED->value)->toBe('approved');
    expect(JobApplicationStatus::REJECTED->value)->toBe('rejected');
    expect(JobApplicationStatus::REQUEST_ADDITIONAL_INFORMATION->value)->toBe('request additional information');

    expect(JobApplicationStatus::PENDING->label())->toBe('Pending');
    expect(JobApplicationStatus::APPROVED->label())->toBe('Approved');
    expect(JobApplicationStatus::REJECTED->label())->toBe('Rejected');
    expect(JobApplicationStatus::REQUEST_ADDITIONAL_INFORMATION->label())->toBe('Request Additional Information');
});
