<?php

declare(strict_types=1);

use App\Actions\MockInterviewAction;
use App\Actions\DeclineMockInterviewAction;
use App\Enums\JobApplicationStatus;
use App\Enums\MockInterviewStatus;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;

beforeEach(function (): void {
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
        $action = app(DeclineMockInterviewAction::class);
        $action->handle($this->application);

        $this->application->refresh();
        expect($this->application->mock_interview_status)->toBe(MockInterviewStatus::DECLINED);
    });

    it('returns the updated application', function (): void {
        $action = app(DeclineMockInterviewAction::class);
        $result = $action->handle($this->application);

        expect($result)->toBeInstanceOf(JobApplication::class);
        expect($result->mock_interview_status)->toBe(MockInterviewStatus::DECLINED);
    });
});