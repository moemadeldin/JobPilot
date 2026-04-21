<?php

declare(strict_types=1);

namespace App\Actions\CustomJobVacancy;

use App\DTOs\CreateCustomJobApplicationDTO;
use App\Models\CustomJobApplication;
use App\Models\CustomJobVacancy;
use App\Models\User;
use App\Services\EvaluateResumeWithAIService;

final readonly class CreateCustomJobApplicationAction
{
    public function __construct(
        private EvaluateResumeWithAIService $evaluateService,
    ) {}

    public function handle(
        CreateCustomJobApplicationDTO $dto,
        CustomJobVacancy $customJobVacancy,
        User $user
    ): CustomJobApplication {
        $user->load('resume');

        $resume = $user->resume;

        abort_if(! $resume || ! $resume->extracted_text, 422, 'Resume not found or has no extracted text.');

        $jobDescription = $this->buildJobDescription($customJobVacancy);

        $evaluation = $this->evaluateService->evaluate(
            $resume->extracted_text,
            $jobDescription
        );

        return CustomJobApplication::query()->create([
            'user_id' => $user->id,
            'custom_job_vacancy_id' => $customJobVacancy->id,
            'compatibility_score' => $evaluation['score'],
            'feedback' => $evaluation['feedback'],
            'improvement_suggestions' => $evaluation['suggestions'],
            ...$dto->toArray(),
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
