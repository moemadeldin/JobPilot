<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Owner;

use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Controllers\API\V1\BaseJobVacancyController;
use App\Http\Requests\JobVacancyFilterRequest;
use App\Http\Resources\JobVacancyResource;
use App\Models\User;
use App\Queries\FilteredJobVacancyQuery;
use App\Traits\APIResponses;
use App\Utilities\Constants;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;

final class JobVacancyController extends BaseJobVacancyController
{
    use APIResponses;

    public function __construct(
        private readonly FilteredJobVacancyQuery $query
    ) {}

    public function index(#[CurrentUser] User $user, JobVacancyFilterRequest $request): JsonResponse
    {
        return $this->success(JobVacancyResource::collection(
            $this->query->builder($request->validated(), $user)
                ->paginate(Constants::NUMBER_OF_PAGINATED_JOB_VACANCIES)
        ), SuccessMessages::FILTERED_SUCCESS->value);
    }
}
