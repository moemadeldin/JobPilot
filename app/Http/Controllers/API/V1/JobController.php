<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\ApplyToJobAction;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Requests\JobVacancyFilterRequest;
use App\Http\Requests\StoreJobApplicationRequest;
use App\Http\Resources\JobApplicationResource;
use App\Http\Resources\JobVacancyResource;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use App\Queries\FilteredJobVacancyQuery;
use App\Traits\APIResponses;
use App\Utilities\Constants;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class JobController
{
    use APIResponses;

    public function __construct(
        private FilteredJobVacancyQuery $query
    ) {}

    public function index(JobVacancyFilterRequest $request): JsonResponse
    {
        return $this->success(JobVacancyResource::collection(
            $this->query->builder($request->validated())
                ->paginate(Constants::NUMBER_OF_PAGINATED_JOB_VACANCIES)
        ), SuccessMessages::FILTERED_SUCCESS->value);
    }

    public function show(JobVacancy $job): JsonResponse
    {
        return $this->success(new JobVacancyResource($job), SuccessMessages::JOB_VACANCY_RETRIEVED->value);
    }

    public function store(
        #[CurrentUser] User $user,
        JobVacancy $job,
        StoreJobApplicationRequest $request,
        ApplyToJobAction $action): JsonResponse
    {
        $resume = Resume::query()
            ->where('id', $request->safe()->resume_id)
            ->first();

        $application = $action->handle(
            $user,
            $job,
            $resume,
            $request->safe()->cover_letter
        );

        return $this->success(new JobApplicationResource($application), SuccessMessages::APPLICATION_SUBMITTED->value, Response::HTTP_CREATED);

    }
}
