<?php

declare(strict_types=1);

namespace App\Actions\CustomJobVacancy;

use App\Enums\MockInterviewStatus;
use App\Models\CustomJobApplication;
use App\Models\CustomJobVacancy;
use App\Models\MockInterview;
use App\Models\MockInterviewQuestion;
use App\Models\User;
use App\Services\EvaluateResumeWithAIService;
use App\Services\GenerateMockInterviewQAService;
use App\Services\ParseJobVacancyService;
use App\Utilities\Constants;
use Illuminate\Http\Response;

final readonly class CreateCustomJobVacancyAction
{
    public function __construct(
        private ParseJobVacancyService $parseService,
        private EvaluateResumeWithAIService $evaluateService,
        private GenerateMockInterviewQAService $generateService,
    ) {}

    /**
     * @return array{vacancy: CustomJobVacancy, application: CustomJobApplication, mock_interview: ?MockInterview}
     */
    public function handle(string $jobText, User $user): array
    {
        $parsed = $this->parseService->parse($jobText);

        $vacancy = CustomJobVacancy::query()->create([
            'title' => $parsed['title'],
            'company' => $parsed['company'],
            'description' => $parsed['description'],
            'location' => $parsed['location'],
            'employment_type' => $parsed['employment_type'] ?? 'full-time',
            'responsibilities' => $parsed['responsibilities'],
            'requirements' => $parsed['requirements'],
            'skills_required' => $parsed['skills_required'],
            'experience_years_min' => $parsed['experience_years_min'],
            'experience_years_max' => $parsed['experience_years_max'],
            'expected_salary' => $parsed['expected_salary'],
            'category' => $parsed['category'],
            'user_id' => $user->id,
        ]);

        $user->load('resume');
        $resume = $user->resume;

        abort_if(! $resume || ! $resume->extracted_text, Response::HTTP_UNPROCESSABLE_ENTITY, 'Resume not found or has no extracted text.');

        $evaluation = $this->evaluateService->evaluate(
            $resume->extracted_text,
            $jobText
        );

        $score = (int) ($evaluation['score'] ?? 0);
        $mockInterviewStatus = $score >= Constants::MINIMUM_SCORE
            ? MockInterviewStatus::SUGGESTED->value
            : MockInterviewStatus::DISQUALIFIED->value;

        $application = CustomJobApplication::query()->create([
            'user_id' => $user->id,
            'custom_job_vacancy_id' => $vacancy->id,
            'compatibility_score' => $score,
            'feedback' => $evaluation['feedback'],
            'improvement_suggestions' => $evaluation['suggestions'],
            'mock_interview_status' => $mockInterviewStatus,
        ]);

        $mockInterview = null;
        if ($score >= Constants::MINIMUM_SCORE) {
            $qaList = $this->generateService->generate(
                $resume->extracted_text,
                $jobText
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
        }

        return [
            'vacancy' => $vacancy,
            'application' => $application,
            'mock_interview' => $mockInterview,
        ];
    }
}
