<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\DeclineMockInterviewAction;
use App\Actions\MockInterviewAction;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Enums\MockInterviewStatus;
use App\Http\Requests\MockInterviewRequest;
use App\Http\Resources\InterviewQuestionResource;
use App\Models\JobApplication;
use App\Models\MockInterviewQuestion;
use App\Traits\APIResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class MockInterviewController
{
    use APIResponses;

    public function show(MockInterviewRequest $request, JobApplication $application): JsonResponse
    {
        if ($application->mock_interview_status !== MockInterviewStatus::ACCEPTED) {
            return $this->success([], 'Mock interview not accepted or not available.');
        }

        $questions = MockInterviewQuestion::query()
            ->where('job_application_id', $application->id)
            ->orderBy('order')
            ->get();

        if ($questions->isEmpty()) {
            return $this->success([], 'No mock interview questions available.');
        }

        return $this->success(InterviewQuestionResource::collection($questions), SuccessMessages::MOCK_INTERVIEW_EXPECTED_QUESTIONS->value);
    }

    public function store(MockInterviewRequest $request, JobApplication $application, MockInterviewAction $action): JsonResponse
    {
        if ($application->mock_interview_status === MockInterviewStatus::ACCEPTED) {
            return $this->fail('Mock interview already accepted for this application.', Response::HTTP_CONFLICT);
        }

        $mockInterview = $action->handle($application);

        return $this->success($mockInterview, 'Mock interview accepted and questions generated.', Response::HTTP_CREATED);
    }

    public function destroy(MockInterviewRequest $request, JobApplication $application, DeclineMockInterviewAction $action): JsonResponse|Response
    {
        if ($application->mock_interview_status !== MockInterviewStatus::SUGGESTED) {
            return $this->fail('Mock interview already accepted for this application.', Response::HTTP_CONFLICT);
        }

        $action->handle($application);

        return $this->noContent();
    }
}
