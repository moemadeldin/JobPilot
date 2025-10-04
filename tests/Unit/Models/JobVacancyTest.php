<?php

declare(strict_types=1);

use App\Enums\EmploymentType;
use App\Enums\Status;
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

test('job vacancy scope filter by job_category_id', function (): void {

    $categoryA = JobCategory::factory()->create();
    $categoryB = JobCategory::factory()->create();

    JobVacancy::factory()->count(3)->create(['job_category_id' => $categoryA->id]);
    JobVacancy::factory()->count(2)->create(['job_category_id' => $categoryB->id]);

    $result = JobVacancy::query()
        ->filterJobCategory($categoryA->id)
        ->get();

    expect($result)->toHaveCount(3);
    expect($result->pluck('job_category_id')->unique()->first())->toBe($categoryA->id);
});
test('job vacancy scope filter by employment_type', function (): void {

    JobVacancy::factory()->count(3)->create(['employment_type' => EmploymentType::REMOTELY->value]);
    JobVacancy::factory()->count(2)->create(['employment_type' => EmploymentType::HYBRID->value]);

    $result = JobVacancy::query()
        ->filterEmploymentType(EmploymentType::REMOTELY->value)
        ->get();

    expect($result)->toHaveCount(3);
    expect($result->pluck('employment_type')->unique()->first())->toBe(EmploymentType::REMOTELY);
});
test('job vacancy scope filter by status', function (): void {

    JobVacancy::factory()->count(3)->create(['is_active' => Status::ACTIVE->value]);
    JobVacancy::factory()->count(2)->create(['is_active' => Status::INACTIVE->value]);

    $result = JobVacancy::query()
        ->filterStatus(Status::ACTIVE->value)
        ->get();

    expect($result)->toHaveCount(3);
    expect($result->pluck('is_active')->unique()->first())->toBe(Status::ACTIVE);
});
test('job vacancy scope filter by location', function (): void {

    JobVacancy::factory()->count(3)->create(['location' => 'USA']);
    JobVacancy::factory()->count(2)->create(['location' => 'Canada']);

    $result = JobVacancy::query()
        ->filterLocation('USA')
        ->get();

    expect($result)->toHaveCount(3);
    expect($result->pluck('location')->unique()->first())->toBe('USA');
});
