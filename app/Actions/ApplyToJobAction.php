<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\JobApplicationStatus;
use App\Enums\MockInterviewStatus;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use App\Services\EvaluateResumeWithAIService;
use Illuminate\Support\Facades\DB;

final readonly class ApplyToJobAction
{
    public function __construct(private EvaluateResumeWithAIService $aiEvaluator) {}

    public function handle(User $user, JobVacancy $job, Resume $resume, ?string $coverLetter = null): JobApplication
    {
        return DB::transaction(function () use ($user, $job, $resume, $coverLetter): JobApplication {

            $application = JobApplication::query()->create([
                'user_id' => $user->id,
                'job_vacancy_id' => $job->id,
                'resume_id' => $resume->id,
                'cover_letter' => $coverLetter,
                'status' => JobApplicationStatus::PENDING->value,
                'applied_at' => now(),
            ]);

            $this->updateJobApplicationWithEvaluation($application, $resume, $job);

            return $application;
        });
    }

    private function updateJobApplicationWithEvaluation(JobApplication $application, Resume $resume, JobVacancy $job): void
    {
        $evaluation = $this->aiEvaluator->evaluate(
            $resume->extracted_text,
            $job->description
        );
        $mockInterviewStatus = ($evaluation['score'] >= 70) ? MockInterviewStatus::SUGGESTED->value : MockInterviewStatus::DISQUALIFIED->value;
        $application->update([
            'compatibility_score' => $evaluation['score'],
            'feedback' => $evaluation['feedback'],
            'improvement_suggestions' => $evaluation['suggestions'],
            'mock_interview_status' => $mockInterviewStatus,
            'reviewed_at' => now(),
        ]);
    }
}
