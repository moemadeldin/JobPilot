<?php

declare(strict_types=1);

use App\Models\JobVacancy;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can see listing of jobs', function (): void {

    $user = User::factory()->create();

    $jobVacancy = JobVacancy::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(route('jobs.index'));

    $response->assertOk();

    $response->assertJsonStructure([
        'status',
        'message',
        'data' => [
            '*' => [
                'job' => [
                    'id',
                    'job_category_id',
                    'company_id',
                    'title',
                    'description',
                    'location',
                    'expected_salary',
                    'employment_type',
                    'status',
                ],
            ],
        ],
    ]);

    $response->assertJsonFragment([
        'id' => $jobVacancy->id,
        'job_category_id' => $jobVacancy->job_category_id,
        'company_id' => $jobVacancy->company_id,
        'title' => $jobVacancy->title,
        'description' => $jobVacancy->description,
        'location' => $jobVacancy->location,
        'expected_salary' => $jobVacancy->expected_salary,
        'employment_type' => $jobVacancy->employment_type->label(),
        'status' => $jobVacancy->is_active->label(),
    ]);
});
it('can view a job vacancy', function (): void {

    $user = User::factory()->create();

    $jobVacancy = JobVacancy::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(route('jobs.show', $jobVacancy));

    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            'job' => [
                'id',
                'job_category_id',
                'company_id',
                'title',
                'description',
                'location',
                'expected_salary',
                'employment_type',
                'status',
            ],
        ],
    ]);
    $response->assertJson([
        'data' => [
            'job' => [
                'id' => $jobVacancy->id,
                'job_category_id' => $jobVacancy->job_category_id,
                'company_id' => $jobVacancy->company_id,
                'title' => $jobVacancy->title,
                'description' => $jobVacancy->description,
                'location' => $jobVacancy->location,
                'expected_salary' => $jobVacancy->expected_salary,
                'employment_type' => $jobVacancy->employment_type->label(),
                'status' => $jobVacancy->is_active->label(),
            ],
        ],
    ]);
});
