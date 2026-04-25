<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\CustomJobVacancy\CreateCustomJobVacancyAction;
use App\Actions\CustomJobVacancy\DeleteCustomJobVacancyAction;
use App\Http\Requests\DeleteCustomJobVacancyRequest;
use App\Http\Requests\StoreCustomJobVacancyRequest;
use App\Http\Resources\CustomJobApplicationResource;
use App\Http\Resources\CustomJobVacancyResource;
use App\Http\Resources\MockInterviewResource;
use App\Models\CustomJobVacancy;
use App\Models\User;
use App\Traits\APIResponses;
use App\Utilities\Constants;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class CustomJobVacancyController
{
    use APIResponses;

    public function index(#[CurrentUser] User $user)
    {
        $vacancies = $user->customJobVacancies()
            ->latest()
            ->paginate(Constants::NUMBER_OF_PAGINATED_JOB_VACANCIES);

        return CustomJobVacancyResource::collection($vacancies);
    }

    public function store(
        StoreCustomJobVacancyRequest $request,
        CreateCustomJobVacancyAction $action,
        #[CurrentUser] User $user
    ): JsonResponse {
        $result = $action->handle($request->safe()->job_text, $user);

        return $this->success([
            'vacancy' => new CustomJobVacancyResource($result['vacancy']),
            'application' => new CustomJobApplicationResource($result['application']),
            'mock_interview' => $result['mock_interview']
                ? new MockInterviewResource($result['mock_interview'])
                : null,
        ], 'Job Vacancy Created Successfully.', Response::HTTP_CREATED);
    }

    public function show(CustomJobVacancy $customJobVacancy): JsonResponse
    {
        return $this->success($customJobVacancy, '');
    }

    public function destroy(
        DeleteCustomJobVacancyRequest $request,
        DeleteCustomJobVacancyAction $action,
        CustomJobVacancy $customJobVacancy
    ): Response {
        $action->handle($customJobVacancy);

        return $this->noContent();
    }
}
