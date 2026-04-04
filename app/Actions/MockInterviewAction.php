<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\MockInterviewStatus;
use App\Models\JobApplication;
use App\Models\MockInterviewQuestion;
use App\Services\GenerateMockInterviewQAService;
use Exception;
use Illuminate\Support\Facades\DB;

final readonly class MockInterviewAction
{
    public function __construct(
        private GenerateMockInterviewQAService $mockInterviewService
    ) {}

    /**
     * @return array<array<string, mixed>>
     */
    public function handle(JobApplication $application): array
    {
        return DB::transaction(function () use ($application): array {

            $application->update([
                'mock_interview_status' => MockInterviewStatus::ACCEPTED->value,
            ]);

            $resume = $application->resume;
            $jobVacancy = $application->jobVacancy;

            throw_if(
                $resume === null || $jobVacancy === null,
                Exception::class,
                'Resume or Job Vacancy not found'
            );

            $resumeText = (string) $resume->extracted_text;
            $jobDescription = (string) $jobVacancy->description;

            $qaList = $this->mockInterviewService->generate($resumeText, $jobDescription);

            $questions = [];

            foreach ($qaList as $index => $qa) {
                /** @var mixed $qa */
                if (! is_array($qa)) {
                    continue;
                }

                if (! array_key_exists('question', $qa)) {
                    continue;
                }

                if (! array_key_exists('answer', $qa)) {
                    continue;
                }

                $questionText = is_string($qa['question']) ? $qa['question'] : '';
                $answerText = is_string($qa['answer']) ? $qa['answer'] : '';

                $question = MockInterviewQuestion::query()->create([
                    'job_application_id' => $application->id,
                    'question' => $questionText,
                    'answer' => $answerText,
                    'order' => $index + 1,
                ]);

                $questions[] = [
                    'order' => $question->order,
                    'question' => $question->question,
                    'answer' => $question->answer,
                ];
            }

            return $questions;
        });
    }
}
