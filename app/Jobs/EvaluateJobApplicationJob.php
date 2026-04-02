<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\MockInterviewStatus;
use App\Models\JobApplication;
use App\Services\EvaluateResumeWithAIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

final class EvaluateJobApplicationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(private readonly JobApplication $application) {}

    public function handle(EvaluateResumeWithAIService $aiEvaluator): void
    {
        $application = $this->application->load(['resume', 'jobVacancy']);

        $resume = $application->resume;
        $job = $application->jobVacancy;

        if (empty($resume->extracted_text)) {
            throw new RuntimeException(sprintf('Resume [%s] has no extracted text to evaluate.', $resume->id));
        }

        $evaluation = $aiEvaluator->evaluate(
            (string) $resume->extracted_text,
            (string) $job->description
        );

        $mockInterviewStatus = $evaluation['score'] >= 70
            ? MockInterviewStatus::SUGGESTED->value
            : MockInterviewStatus::DISQUALIFIED->value;

        $application->update([
            'compatibility_score' => $evaluation['score'],
            'feedback' => $evaluation['feedback'],
            'improvement_suggestions' => $evaluation['suggestions'],
            'mock_interview_status' => $mockInterviewStatus,
            'reviewed_at' => now(),
        ]);
    }
}
