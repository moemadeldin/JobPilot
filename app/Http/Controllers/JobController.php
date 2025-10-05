<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Requests\JobVacancyFilterRequest;
use App\Http\Resources\JobVacancyResource;
use App\Models\JobVacancy;
use App\Queries\FilteredJobVacancyQuery;
use App\Traits\APIResponses;
use Illuminate\Http\JsonResponse;

final class JobController extends Controller
{
    use APIResponses;

    public function __construct(
        private readonly FilteredJobVacancyQuery $query
    ) {}

    public function index(JobVacancyFilterRequest $request): JsonResponse
    {
        return $this->success(JobVacancyResource::collection(
            $this->query->builder($request->validated())
                ->paginate(JobVacancy::NUMBER_OF_PAGINATED_JOB_VACANCIES)
        ), SuccessMessages::FILTERED_SUCCESS->value);
    }

    public function show(JobVacancy $jobVacancy): JsonResponse
    {
        return $this->success(new JobVacancyResource($jobVacancy), '');
    }
}
