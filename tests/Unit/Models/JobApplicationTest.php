<?php

declare(strict_types=1);

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('job application belongs to user', function (): void {
    $user = User::factory()->create();

    $jobApplication = JobApplication::factory()->create([
        'user_id' => $user->id,
    ]);

    expect($jobApplication->user)->toBeInstanceOf(User::class);
    expect($jobApplication->user_id)->toBe($user->id);
});

test('job application belongs to job vacancy', function (): void {
    $jobVacancy = JobVacancy::factory()->create();

    $jobApplication = JobApplication::factory()->create([
        'job_vacancy_id' => $jobVacancy->id,
    ]);

    expect($jobApplication->jobVacancy)->toBeInstanceOf(JobVacancy::class);
    expect($jobApplication->job_vacancy_id)->toBe($jobVacancy->id);
});
