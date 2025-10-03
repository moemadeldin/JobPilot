<?php

declare(strict_types=1);

use App\Models\ApplicationAnalytic;
use App\Models\Company;
use App\Models\JobAnalytic;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('job vacancy belongs to job category', function (): void {
    $jobCategory = JobCategory::factory()->create();

    $jobVacancy = JobVacancy::factory()->create([
        'job_category_id' => $jobCategory->id,
    ]);

    expect($jobVacancy->category)->toBeInstanceOf(JobCategory::class);
    expect($jobVacancy->category->id)->toBe($jobCategory->id);
});

test('job vacancy belongs to company', function (): void {
    $company = Company::factory()->create();

    $jobVacancy = JobVacancy::factory()->create([
        'company_id' => $company->id,
    ]);

    expect($jobVacancy->company)->toBeInstanceOf(Company::class);
    expect($jobVacancy->company->id)->toBe($company->id);
});

test('job vacancy has many applications', function (): void {

    $jobVacancy = JobVacancy::factory()->create();
    $jobApplication = JobApplication::factory()->for($jobVacancy)->count(3)->create();

    expect($jobVacancy->applications->first())->toBeInstanceOf(JobApplication::class);
    expect($jobVacancy->applications)->toHaveCount(3);
});
test('job vacancy has many job analytics', function (): void {

    $jobVacancy = JobVacancy::factory()->create();
    $jobAnalytics = JobAnalytic::factory()->for($jobVacancy)->count(3)->create();

    expect($jobVacancy->jobAnalytics->first())->toBeInstanceOf(JobAnalytic::class);
    expect($jobVacancy->jobAnalytics)->toHaveCount(3);
});
test('job vacancy has many application analytics', function (): void {

    $jobVacancy = JobVacancy::factory()->create();
    $applicationAnalytics = ApplicationAnalytic::factory()->for($jobVacancy)->count(3)->create();

    expect($jobVacancy->applicationAnalytics->first())->toBeInstanceOf(ApplicationAnalytic::class);
    expect($jobVacancy->applicationAnalytics)->toHaveCount(3);
});
