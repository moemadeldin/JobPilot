<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\MockInterviewStatus;
use App\Models\CustomJobApplication;
use App\Models\CustomJobVacancy;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Services\EvaluateResumeWithAIService;
use App\Utilities\Constants;
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

    public function __construct(private readonly CustomJobApplication $application) {}

    public function handle(EvaluateResumeWithAIService $aiEvaluator): void
    {
        $application = $this->application->load(['resume', 'jobVacancy']);

        /** @var Resume $resume */
        $resume = $application->resume;
        /** @var CustomJobVacancy $job */
        $job = $application->jobVacancy;

        if (empty($resume->extracted_text)) {
            throw new RuntimeException(sprintf('Resume [%s] has no extracted text to evaluate.', $resume->id));
        }

        $evaluation = $aiEvaluator->evaluate(
            (string) $resume->extracted_text,
            (string) $job->description
        );
        $score = (int) ($evaluation['score'] ?? 0);

        $mockInterviewStatus = $score >= Constants::MINIMUM_SCORE
            ? MockInterviewStatus::SUGGESTED->value
            : MockInterviewStatus::DISQUALIFIED->value;

        $application->update([
            'compatibility_score' => $evaluation['score'],
            'feedback' => $evaluation['feedback'],
            'improvement_suggestions' => $evaluation['suggestions'],
            'applied_at' => now(),
            'reviewed_at' => now(),
        ]);
    }
}
