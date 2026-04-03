<?php

declare(strict_types=1);

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
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
                'id',
                'category',
                'company',
                'title',
                'description',
                'location',
                'expected_salary',
                'employment_type',
                'status',
            ],
        ],
    ]);

    $response->assertJsonFragment([
        'id' => $jobVacancy->id,
        'category' => $jobVacancy->category->name,
        'company' => $jobVacancy->company->name,
        'title' => $jobVacancy->title,
        'description' => $jobVacancy->description,
        'location' => $jobVacancy->location,
        'expected_salary' => $jobVacancy->expected_salary,
        'employment_type' => $jobVacancy->employment_type->label(),
        'status' => $jobVacancy->status->label(),
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
            'id',
            'category',
            'company',
            'title',
            'description',
            'location',
            'expected_salary',
            'employment_type',
            'status',
        ],
    ]);
    $response->assertJson([
        'data' => [
            'id' => $jobVacancy->id,
            'category' => $jobVacancy->category->name,
            'company' => $jobVacancy->company->name,
            'title' => $jobVacancy->title,
            'description' => $jobVacancy->description,
            'location' => $jobVacancy->location,
            'expected_salary' => $jobVacancy->expected_salary,
            'employment_type' => $jobVacancy->employment_type->label(),
            'status' => $jobVacancy->status->label(),
        ],
    ]);
});

it('can apply for a job', function (): void {
    $user = User::factory()->create();
    $job = JobVacancy::factory()->create();

    $resume = Resume::factory()->for($user)->create([
        'extracted_text' => 'Backend Developer skilled in Laravel, PHP, MySQL',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('jobs.store', $job), [
        'resume_id' => $resume->id,
    ]);

    $response->assertCreated();
    $response->assertJsonStructure([
        'data' => [
            'id',
            'user_id',
            'job_vacancy_id',
            'resume_id',
            'cover_letter',
            'status',
            'evaluation' => [
                'compatibility_score',
                'feedback' => [
                    'strengths',
                    'weaknesses',
                ],
                'improvement_suggestions',
                'reviewed_at',
            ],
            'mock_interview_status',
            'applied_at',
            'created_at',
            'updated_at',
        ],
    ]);
    $response->assertJsonFragment([
        'user_id' => $user->id,
        'job_vacancy_id' => $job->id,
        'resume_id' => $resume->id,
    ]);
});

it('can apply for a job with cover letter', function (): void {
    $user = User::factory()->create();
    $job = JobVacancy::factory()->create();

    $resume = Resume::factory()->for($user)->create([
        'extracted_text' => 'Backend Developer skilled in Laravel',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('jobs.store', $job), [
        'resume_id' => $resume->id,
        'cover_letter' => 'I am very interested in this position',
    ]);

    $response->assertCreated();
    $response->assertJsonFragment([
        'cover_letter' => 'I am very interested in this position',
    ]);
});

it('returns AI evaluation feedback after applying', function (): void {
    $user = User::factory()->create();
    $job = JobVacancy::factory()->create();

    $resume = Resume::factory()->for($user)->create([
        'extracted_text' => 'Backend Developer skilled in Laravel, PHP, MySQL, React',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('jobs.store', $job), [
        'resume_id' => $resume->id,
    ]);

    $response->assertCreated();
    $response->assertJsonStructure([
        'data' => [
            'evaluation' => [
                'compatibility_score',
                'feedback' => [
                    'strengths',
                    'weaknesses',
                ],
                'improvement_suggestions',
                'reviewed_at',
            ],
            'mock_interview_status',
        ],
    ]);
});

it('fails when resume does not belong to user', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $job = JobVacancy::factory()->create();

    $resume = Resume::factory()->for($otherUser)->create([
        'extracted_text' => 'Backend Developer',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('jobs.store', $job), [
        'resume_id' => $resume->id,
    ]);

    $response->assertStatus(404);
});

it('fails when resume does not exist', function (): void {
    $user = User::factory()->create();
    $job = JobVacancy::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('jobs.store', $job), [
        'resume_id' => 'non-existent-id',
    ]);

    $response->assertStatus(422);
});