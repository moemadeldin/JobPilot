<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Requests\CustomMockInterviewRequest;
use App\Http\Resources\InterviewQuestionResource;
use App\Models\CustomJobApplication;
use App\Traits\APIResponses;
use Illuminate\Http\JsonResponse;

final readonly class CustomMockInterviewController
{
    use APIResponses;

    public function __invoke(CustomMockInterviewRequest $request, CustomJobApplication $customApplication): JsonResponse
    {
        $questions = $customApplication->mockInterview
            ? $customApplication->mockInterview->questions()->orderBy('order')->get()
            : collect([]);

        if ($questions->isEmpty()) {
            return $this->success([], 'No mock interview questions available.');
        }

        return $this->success(InterviewQuestionResource::collection($questions), SuccessMessages::MOCK_INTERVIEW_EXPECTED_QUESTIONS->value);
    }
}
