<?php

declare(strict_types=1);

namespace App\Actions\CustomJobVacancy;

use App\Enums\MockInterviewStatus;
use App\Models\CustomJobApplication;
use App\Models\CustomJobVacancy;
use App\Models\User;
use App\Services\EvaluateResumeWithAIService;
use App\Utilities\Constants;
use Illuminate\Http\Response;

final readonly class CreateCustomJobApplicationAction
{
    public function __construct(
        private EvaluateResumeWithAIService $evaluateService,
    ) {}

    public function handle(
        string $coverLetter,
        CustomJobVacancy $customJobVacancy,
        User $user
    ): CustomJobApplication {
        $user->load('resume');

        $resume = $user->resume;

        abort_if(! $resume || ! $resume->extracted_text, Response::HTTP_UNPROCESSABLE_ENTITY, 'Resume not found or has no extracted text.');

        $jobDescription = $this->buildJobDescription($customJobVacancy);

        $evaluation = $this->evaluateService->evaluate(
            $resume->extracted_text,
            $jobDescription
        );

        $score = (int) ($evaluation['score'] ?? 0);
        $mockInterviewStatus = $score >= Constants::MINIMUM_SCORE
            ? MockInterviewStatus::SUGGESTED->value
            : MockInterviewStatus::DISQUALIFIED->value;

        return CustomJobApplication::query()->create([
            'user_id' => $user->id,
            'custom_job_vacancy_id' => $customJobVacancy->id,
            'compatibility_score' => $score,
            'feedback' => $evaluation['feedback'],
            'improvement_suggestions' => $evaluation['suggestions'],
            'cover_letter' => $coverLetter,
            'mock_interview_status' => $mockInterviewStatus,
        ]);
    }

    private function buildJobDescription(CustomJobVacancy $vacancy): string
    {
        $parts = array_filter([
            $vacancy->title,
            $vacancy->description,
            $vacancy->responsibilities ? 'Responsibilities: '.$vacancy->responsibilities : null,
            $vacancy->requirements ? 'Requirements: '.$vacancy->requirements : null,
            $vacancy->skills_required ? 'Skills: '.$vacancy->skills_required : null,
            $vacancy->location ? 'Location: '.$vacancy->location : null,
            $vacancy->experience_years_min || $vacancy->experience_years_max
                ? sprintf('Experience: %s-%s years', $vacancy->experience_years_min, $vacancy->experience_years_max)
                : null,
        ]);

        return implode("\n\n", $parts);
    }
}
