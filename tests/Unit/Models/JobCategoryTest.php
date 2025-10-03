<?php

declare(strict_types=1);

use App\Models\JobCategory;
use App\Models\JobVacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('job category has many vacancies', function (): void {
    $jobCategory = JobCategory::factory()->create();

    $vacancies = JobVacancy::factory()->count(3)->create([
        'job_category_id' => $jobCategory->id,
    ]);

    expect($jobCategory->vacancies->first())->toBeInstanceOf(JobVacancy::class);
    expect($jobCategory->vacancies)->toHaveCount(3);
});
