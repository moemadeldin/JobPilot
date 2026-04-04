<?php

declare(strict_types=1);

use App\Enums\JobApplicationStatus;
use App\Jobs\EvaluateJobApplicationJob;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use App\Services\EvaluateResumeWithAIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;

uses(RefreshDatabase::class);

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
    ]);
});

describe('EvaluateJobApplicationJob', function (): void {
    it('throws exception when resume has no extracted text', function (): void {
        $this->resume->update(['extracted_text' => null]);
        $job = new EvaluateJobApplicationJob($this->application);

        $job->handle(resolve(EvaluateResumeWithAIService::class));
    })->throws(RuntimeException::class);
});
