<?php

declare(strict_types=1);

namespace App\Actions\CustomJobVacancy;

use App\Models\CustomJobApplication;
use App\Models\CustomJobVacancy;
use App\Models\MockInterview;
use App\Models\MockInterviewQuestion;
use App\Services\GenerateMockInterviewQAService;

final readonly class GenerateCustomMockInterviewAction
{
    public function __construct(
        private GenerateMockInterviewQAService $generateService,
    ) {}

    public function handle(CustomJobApplication $application): MockInterview
    {
        $application->load(['user.resume', 'customJobVacancy']);

        $user = $application->user;
        $resume = $user->resume;
        $vacancy = $application->customJobVacancy;

        throw_if(! $resume || ! $resume->extracted_text, 'Resume not found or has no extracted text.');

        $jobDescription = $this->buildJobDescription($vacancy);

        $qaList = $this->generateService->generate(
            $resume->extracted_text,
            $jobDescription
        );

        $mockInterview = MockInterview::query()->create([
            'application_id' => $application->id,
        ]);

        $questionsData = collect($qaList)->map(fn ($qa, $index): array => [
            'mock_interview_id' => $mockInterview->id,
            'question' => $qa['question'],
            'answer' => $qa['answer'],
            'order' => $index + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ])->values()->all();

        MockInterviewQuestion::query()->insert($questionsData);

        $mockInterview->load('questions');

        return $mockInterview;
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
