<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\JobApplicationStatus;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use App\Services\EvaluateResumeWithAIService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final readonly class ApplyToJobAction
{
    public function __construct(private EvaluateResumeWithAIService $aiEvaluator) {}

    public function handle(User $user, JobVacancy $job, Resume $resume, ?string $coverLetter = null): JobApplication
    {
        return DB::transaction(function () use ($user, $job, $resume, $coverLetter): JobApplication {

            $application = JobApplication::create([
                'user_id' => $user->id,
                'job_vacancy_id' => $job->id,
                'resume_id' => $resume->id,
                'cover_letter' => $coverLetter,
                'status' => JobApplicationStatus::PENDING->value,
                'applied_at' => now(),
            ]);

            Log::info('About to evaluate resume', [
                'resume_text_length' => mb_strlen($resume->extracted_text ?? ''),
                'job_desc_length' => mb_strlen($job->description ?? ''),
            ]);

            $evaluation = $this->aiEvaluator->evaluate(
                $resume->extracted_text,
                $job->description
            );

            Log::info('AI Evaluation Result', [
                'evaluation' => $evaluation,
                'evaluation_type' => gettype($evaluation),
                'score_type' => gettype($evaluation['score'] ?? null),
                'feedback_type' => gettype($evaluation['feedback'] ?? null),
            ]);

            try {
                $application->compatibility_score = $evaluation['score'];
                $application->save();
                Log::info('✅ Score updated successfully');
            } catch (Throwable $e) {
                Log::error('❌ Error updating score: '.$e->getMessage());
                throw $e;
            }

            try {
                $application->feedback = $evaluation['feedback'];
                $application->save();
                Log::info('✅ Feedback updated successfully');
            } catch (Throwable $e) {
                Log::error('❌ Error updating feedback: '.$e->getMessage());
                throw $e;
            }

            try {
                $application->improvement_suggestions = $evaluation['suggestions'];
                $application->save();
                Log::info('✅ Suggestions updated successfully');
            } catch (Throwable $e) {
                Log::error('❌ Error updating suggestions: '.$e->getMessage());
                throw $e;
            }

            try {
                $application->reviewed_at = now();
                $application->save();
                Log::info('✅ Reviewed_at updated successfully');
            } catch (Throwable $e) {
                Log::error('❌ Error updating reviewed_at: '.$e->getMessage());
                throw $e;
            }

            return $application;
        });
    }
}
