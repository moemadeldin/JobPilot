<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\MockInterviewStatus;
use App\Models\JobApplication;
use App\Models\MockInterviewQuestion;
use App\Services\GenerateMockInterviewQAService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

final readonly class MockInterviewAction
{
    public function __construct(private GenerateMockInterviewQAService $mockInterviewService) {}

    public function handle(JobApplication $application): array|JsonResponse
    {
        return DB::transaction(function () use ($application): array {

            $application->update(['mock_interview_status' => MockInterviewStatus::ACCEPTED->value]);

            $resumeText = $application->resume->extracted_text;
            $jobDescription = $application->jobVacancy->description;
            $qaList = $this->mockInterviewService->generate($resumeText, $jobDescription);

            $questions = [];

            foreach ($qaList as $index => $qa) {
                $question = MockInterviewQuestion::query()->create([
                    'job_application_id' => $application->id,
                    'question' => $qa['question'],
                    'answer' => $qa['answer'],
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
