<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\CustomJobVacancy\GenerateCustomMockInterviewAction;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\MockInterviewRequest;
use App\Http\Resources\InterviewQuestionResource;
use App\Http\Resources\MockInterviewResource;
use App\Models\CustomJobApplication;
use App\Models\CustomJobVacancy;
use App\Models\MockInterviewQuestion;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class CustomMockInterviewController extends Controller
{
    use APIResponses;

    public function show(MockInterviewRequest $request, CustomJobApplication $application): JsonResponse
    {

        $questions = MockInterviewQuestion::query()
            ->where('job_application_id', $application->id)
            ->orderBy('order')
            ->get();

        if ($questions->isEmpty()) {
            return $this->success([], 'No mock interview questions available.');
        }

        return $this->success(InterviewQuestionResource::collection($questions), SuccessMessages::MOCK_INTERVIEW_EXPECTED_QUESTIONS->value);
    }
}
