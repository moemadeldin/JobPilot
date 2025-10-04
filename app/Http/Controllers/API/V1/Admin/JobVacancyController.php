<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\JobVacancy\CreateJobVacancyAction;
use App\Actions\JobVacancy\DeleteJobVacancyAction;
use App\Actions\JobVacancy\UpdateJobVacancyAction;
use App\DTOs\CreateJobVacancyDTO;
use App\DTOs\UpdateJobVacancyDTO;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobVacancyFilterRequest;
use App\Http\Requests\StoreJobVacancyRequest;
use App\Http\Requests\UpdateJobVacancyRequest;
use App\Http\Resources\JobVacancyResource;
use App\Models\JobVacancy;
use App\Traits\APIResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class JobVacancyController extends Controller
{
    use APIResponses;

    public function index(JobVacancyFilterRequest $request)
    {
        return $this->success(JobVacancyResource::collection(
            JobVacancy::query()
                ->with(['company', 'category'])
                ->filterJobCategory($request->safe()->job_category_id)
                ->filterEmploymentType($request->safe()->employment_type)
                ->filterStatus($request->safe()->is_active)
                ->filterLocation($request->safe()->location)
                ->paginate(6)
        ), 'filtered successfully');
    }

    public function show(JobVacancy $jobVacancy): JsonResponse
    {
        return $this->success(new JobVacancyResource($jobVacancy), '');
    }

    public function store(StoreJobVacancyRequest $request, CreateJobVacancyAction $action): JsonResponse
    {

        return $this->success(
            $action->handle(CreateJobVacancyDTO::fromArray($request->validated())), SuccessMessages::JOB_VACANCY_CREATED->value, Response::HTTP_CREATED);
    }

    public function update(UpdateJobVacancyRequest $request, UpdateJobVacancyAction $action, JobVacancy $jobVacancy): JsonResponse
    {
        return $this->success(
            $action->handle(UpdateJobVacancyDTO::fromArray($request->validated()), $jobVacancy), SuccessMessages::JOB_VACANCY_UPDATED->value);
    }

    public function destroy(DeleteJobVacancyAction $action, JobVacancy $jobVacancy): Response
    {
        $action->handle($jobVacancy);

        return $this->noContent();
    }
}
