<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\CustomMockInterviewAction;
use App\Actions\DeclineMockInterviewAction;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Enums\MockInterviewStatus;
use App\Http\Requests\CustomMockInterviewRequest;
use App\Http\Resources\InterviewQuestionResource;
use App\Models\CustomJobApplication;
use App\Models\MockInterviewQuestion;
use App\Traits\APIResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class CustomMockInterviewController
{
    use APIResponses;

    public function show(CustomMockInterviewRequest $request, CustomJobApplication $customApplication): JsonResponse
    {

    $questions = $customApplication->mockInterview 
        ? $customApplication->mockInterview->questions()->orderBy('order')->get() 
        : collect([]);

        if ($questions->isEmpty()) {
            return $this->success([], 'No mock interview questions available.');
        }

        return $this->success(InterviewQuestionResource::collection($questions), SuccessMessages::MOCK_INTERVIEW_EXPECTED_QUESTIONS->value);
    }

    public function store(CustomMockInterviewRequest $request, CustomJobApplication $customApplication, CustomMockInterviewAction $action): JsonResponse
    {
        if ($customApplication->mockInterview->status === MockInterviewStatus::ACCEPTED) {
            return $this->fail('Mock interview already accepted for this application.', Response::HTTP_CONFLICT);
        }

        $mockInterview = $action->handle($customApplication);

        return $this->success(['questions' => $mockInterview], 'Mock interview accepted and questions generated.', Response::HTTP_CREATED);
    }

    public function destroy(CustomMockInterviewRequest $request, CustomJobApplication $customApplication, DeclineMockInterviewAction $action): JsonResponse|Response
    {
        if ($customApplication->mockInterview->status !== MockInterviewStatus::SUGGESTED) {
            return $this->fail('Mock interview already accepted for this application.', Response::HTTP_CONFLICT);
        }

        $action->handle($customApplication);

        return $this->noContent();
    }
}
