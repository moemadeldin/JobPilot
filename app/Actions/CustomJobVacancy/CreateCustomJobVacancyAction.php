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
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($jobText, $user) {

            $parsed = $this->parseService->parse($jobText);
            $vacancy = $this->createVacancy($user, $parsed);

            $user->load('resume');
            abort_if(
                ! $user->resume || ! $user->resume->extracted_text,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'Resume not found or has no extracted text.'
            );

            $resumeText = $user->resume->extracted_text;

            $evaluation = $this->evaluateService->evaluate($resumeText, $jobText);
            $score = (int) ($evaluation['score'] ?? 0);

            $application = $this->createApplication(
                $user,
                $vacancy,
                $evaluation,
                $score
            );

            $mockInterview = $this->createMockInterview(
                $score,
                $resumeText,
                $jobText,
                $user,
                $application
            );

            return [
                'vacancy' => $vacancy,
                'application' => $application,
                'mock_interview' => $mockInterview,
            ];
        });
    }

    private function createVacancy(User $user, array $parsed): CustomJobVacancy
    {
        return CustomJobVacancy::create([
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
    }

    private function createApplication(
        User $user,
        CustomJobVacancy $vacancy,
        array $evaluation,
        int $score
    ): CustomJobApplication {
        return CustomJobApplication::create([
            'user_id' => $user->id,
            'custom_job_vacancy_id' => $vacancy->id,
            'compatibility_score' => $score,
            'feedback' => $evaluation['feedback'],
            'improvement_suggestions' => $evaluation['suggestions'],
        ]);
    }

    private function createMockInterview(
        int $score,
        string $resumeText,
        string $jobText,
        User $user,
        CustomJobApplication $application
    ): ?MockInterview {
        if ($score < Constants::MINIMUM_SCORE) {
            return null;
        }

        $qaList = $this->generateService->generate($resumeText, $jobText);

        $mockInterview = MockInterview::create([
            'application_id' => $application->id,
            'status' => MockInterviewStatus::SUGGESTED->value,
        ]);

        foreach ($qaList as $index => $qa) {
            MockInterviewQuestion::create([
                'mock_interview_id' => $mockInterview->id,
                'question' => $qa['question'],
                'answer' => $qa['answer'],
                'order' => $index + 1,
            ]);
        }

        return $mockInterview->load('questions');
    }
}
