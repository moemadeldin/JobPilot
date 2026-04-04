<?php

declare(strict_types=1);

use App\Actions\DeclineMockInterviewAction;
use App\Actions\MockInterviewAction;
use App\Enums\JobApplicationStatus;
use App\Enums\MockInterviewStatus;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    Http::fake([
        '*' => Http::response([
            'choices' => [[
                'message' => [
                    'content' => json_encode([
                        'qa' => [
                            ['question' => 'Q1', 'answer' => 'A1'],
                            ['question' => 'Q2', 'answer' => 'A2'],
                        ],
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
    $this->application = JobApplication::factory()->for($this->user)->for($this->jobVacancy)->for($this->resume)->create([
        'status' => JobApplicationStatus::PENDING,
        'mock_interview_status' => MockInterviewStatus::SUGGESTED,
    ]);
});

describe('DeclineMockInterviewAction', function (): void {
    it('updates application status to declined', function (): void {
        $action = resolve(DeclineMockInterviewAction::class);
        $action->handle($this->application);

        $this->application->refresh();
        expect($this->application->mock_interview_status)->toBe(MockInterviewStatus::DECLINED);
    });

    it('returns the updated application', function (): void {
        $action = resolve(DeclineMockInterviewAction::class);
        $result = $action->handle($this->application);

        expect($result)->toBeInstanceOf(JobApplication::class);
        expect($result->mock_interview_status)->toBe(MockInterviewStatus::DECLINED);
    });
});

describe('MockInterviewAction', function (): void {
    it('generates mock interview questions successfully', function (): void {
        $action = resolve(MockInterviewAction::class);
        $result = $action->handle($this->application);

        expect($result)->toHaveCount(2);
        expect($result[0]['question'])->toBe('Q1');
        expect($result[0]['answer'])->toBe('A1');

        $this->application->refresh();
        expect($this->application->mock_interview_status)->toBe(MockInterviewStatus::ACCEPTED);
    });

    it('throws exception when resume is null', function (): void {
        $application = JobApplication::factory()->for($this->user)->for($this->jobVacancy)->create([
            'resume_id' => null,
        ]);

        $action = resolve(MockInterviewAction::class);
        $action->handle($application);
    })->throws(Exception::class);

    it('throws exception when job vacancy is null', function (): void {
        $application = JobApplication::factory()->for($this->user)->for($this->resume)->create([
            'job_vacancy_id' => null,
        ]);

        $action = resolve(MockInterviewAction::class);
        $action->handle($application);
    })->throws(Exception::class);
});
