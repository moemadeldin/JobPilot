<?php

declare(strict_types=1);

use App\Models\ApplicationAnalytic;
use App\Models\JobVacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('application analytic belongs to job vacancy', function (): void {
    $jobVacancy = JobVacancy::factory()->create();
    $applicationAnalytic = ApplicationAnalytic::factory()->for($jobVacancy)->create();

    expect($applicationAnalytic->jobVacancy)->toBeInstanceOf(JobVacancy::class);
    expect($applicationAnalytic->job_vacancy_id)->toBe($jobVacancy->id);
});
