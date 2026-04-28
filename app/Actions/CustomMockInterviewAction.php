<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\MockInterviewStatus;
use App\Models\CustomJobApplication;
use App\Models\MockInterview;
use App\Models\MockInterviewQuestion;
use App\Services\GenerateMockInterviewQAService;
use Exception;
use Illuminate\Support\Facades\DB;

final readonly class CustomMockInterviewAction
{
    public function __construct(
        private GenerateMockInterviewQAService $mockInterviewService
    ) {}

    /**
     * @return array<array<string, mixed>>
     */
    public function handle(CustomJobApplication $application): array
    {
        $application->loadMissing(['mockInterview', 'user.resume', 'customJobVacancy']);

        return DB::transaction(function () use ($application): array {
            $this->qualifyMockInterview($application);

            [$resumeText, $jobDescription] = $this->extractTexts($application);

            $qaList = $this->mockInterviewService->generate($resumeText, $jobDescription);

            return $this->storeQuestions($application, $qaList);
        });
    }

    private function qualifyMockInterview(CustomJobApplication $application): void
    {
        $mockInterview = $application->mockInterview;

        throw_if($mockInterview === null, Exception::class, 'Mock interview not found');

        $mockInterview->update([
            'status' => MockInterviewStatus::QUALIFIED->value,
        ]);
    }

    private function extractTexts(CustomJobApplication $application): array
    {
        $resume = $application->user->resume;
        $customJobVacancy = $application->customJobVacancy;

        throw_if(
            $resume === null || $customJobVacancy === null,
            Exception::class,
            'Resume or Job Vacancy not found'
        );

        return [
            (string) $resume->extracted_text,
            (string) $customJobVacancy->description,
        ];
    }

    private function storeQuestions(CustomJobApplication $application, array $qaList): array
    {
        $mockInterview = MockInterview::query()->create([
            'application_id' => $application->id,
        ]);

        $questionsData = [];
        $questions = [];

        foreach ($qaList as $index => $qa) {
            if (! is_array($qa)) {
                continue;
            }

            if (! isset($qa['question'], $qa['answer'])) {
                continue;
            }

            $questionText = is_string($qa['question']) ? $qa['question'] : '';
            $answerText = is_string($qa['answer']) ? $qa['answer'] : '';

            $questionsData[] = [
                'mock_interview_id' => $mockInterview->id,
                'question' => $questionText,
                'answer' => $answerText,
                'order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $questions[] = [
                'order' => $index + 1,
                'question' => $questionText,
                'answer' => $answerText,
            ];
        }

        if ($questionsData !== []) {
            MockInterviewQuestion::query()->insert($questionsData);
        }

        return $questions;
    }
}
