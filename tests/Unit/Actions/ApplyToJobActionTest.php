<?php

declare(strict_types=1);

use App\Actions\ApplyToJobAction;
use App\Enums\JobApplicationStatus;
use App\Enums\MockInterviewStatus;
use App\Jobs\EvaluateJobApplicationJob;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

beforeEach(function (): void {
    Http::fake([
        '*' => Http::response([
            'choices' => [[
                'message' => [
                    'content' => json_encode([
                        'score' => 85,
                        'feedback' => ['strengths' => ['PHP'], 'weaknesses' => []],
                        'suggestions' => 'Keep learning',
                    ]),
                ],
            ]],
        ], 200),
    ]);

    $this->user = User::factory()->create();
    $this->company = $this->user->companies()->create([
        'name' => 'Test Company',
        'industry' => 'Technology',
        'address' => 'Test Address',
        'website' => 'https://test.com',
    ]);
    $this->jobVacancy = JobVacancy::factory()->for($this->company)->create([
        'title' => 'Backend Developer',
        'description' => 'We are looking for a skilled Laravel developer',
    ]);
    $this->resume = Resume::factory()->for($this->user)->create([
        'extracted_text' => 'Experienced Laravel developer with 5 years experience',
    ]);
});

describe('ApplyToJobAction', function (): void {
    it('creates a job application with pending status', function (): void {
        $action = app(ApplyToJobAction::class);

        $application = $action->handle($this->user, $this->jobVacancy, $this->resume);

        expect($application)->toBeInstanceOf(JobApplication::class);
        expect($application->user_id)->toBe($this->user->id);
        expect($application->job_vacancy_id)->toBe($this->jobVacancy->id);
        expect($application->resume_id)->toBe($this->resume->id);
        expect($application->status)->toBe(JobApplicationStatus::PENDING);
        expect($application->applied_at)->not->toBeNull();
    });

    it('creates application with cover letter', function (): void {
        $action = app(ApplyToJobAction::class);

        $application = $action->handle($this->user, $this->jobVacancy, $this->resume, 'My cover letter');

        expect($application->cover_letter)->toBe('My cover letter');
    });

    it('creates application without cover letter', function (): void {
        $action = app(ApplyToJobAction::class);

        $application = $action->handle($this->user, $this->jobVacancy, $this->resume);

        expect($application->cover_letter)->toBeNull();
    });

    it('sets compatibility score from AI evaluation', function (): void {
        $action = app(ApplyToJobAction::class);

        $application = $action->handle($this->user, $this->jobVacancy, $this->resume);

        expect($application->compatibility_score)->toBeGreaterThanOrEqual(0);
        expect($application->compatibility_score)->toBeLessThanOrEqual(100);
    });

    it('sets mock interview status based on score', function (): void {
        $action = app(ApplyToJobAction::class);

        $application = $action->handle($this->user, $this->jobVacancy, $this->resume);

        expect($application->mock_interview_status)->not->toBeNull();
    });

    it('logs application creation', function (): void {
        Log::shouldReceive('info')
            ->once()
            ->with('About to dispatch job for application', \Mockery::on(fn ($data): bool => isset($data['id'])));

        $action = app(ApplyToJobAction::class);
        $action->handle($this->user, $this->jobVacancy, $this->resume);
    });

    it('sets reviewed_at after evaluation', function (): void {
        $action = app(ApplyToJobAction::class);

        $application = $action->handle($this->user, $this->jobVacancy, $this->resume);

        expect($application->reviewed_at)->not->toBeNull();
    });
});