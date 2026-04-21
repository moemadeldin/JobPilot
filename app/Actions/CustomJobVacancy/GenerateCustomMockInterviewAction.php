<?php

declare(strict_types=1);

namespace App\Actions\CustomJobVacancy;

use App\Models\CustomJobApplication;
use App\Models\CustomJobVacancy;
use App\Models\MockInterview;
use App\Models\MockInterviewQuestion;
use App\Services\GenerateMockInterviewQAService;
use Illuminate\Http\Response;

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

        abort_if(! $resume || ! $resume->extracted_text, Response::HTTP_UNPROCESSABLE_ENTITY, 'Resume not found or has no extracted text.');

        $jobDescription = $this->buildJobDescription($vacancy);

        $qaList = $this->generateService->generate(
            $resume->extracted_text,
            $jobDescription
        );

        $mockInterview = MockInterview::query()->create([
            'user_id' => $user->id,
            'interviewable_id' => $application->id,
            'interviewable_type' => CustomJobApplication::class,
        ]);

        $order = 1;
        foreach ($qaList as $qa) {
            MockInterviewQuestion::query()->create([
                'mock_interview_id' => $mockInterview->id,
                'question' => $qa['question'],
                'answer' => $qa['answer'],
                'order' => $order++,
            ]);
        }

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
