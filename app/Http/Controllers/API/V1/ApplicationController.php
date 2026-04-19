<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\ApplicationListResource;
use App\Http\Resources\JobApplicationResource;
use App\Models\JobApplication;
use App\Models\User;
use App\Queries\UserApplicationQuery;
use App\Traits\APIResponses;
use App\Utilities\Constants;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final readonly class ApplicationController
{
    use APIResponses;

    public function __construct(
        private UserApplicationQuery $query
    ) {}

    public function index(#[CurrentUser] User $user, Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', Constants::NUMBER_OF_PAGINATED_JOB_APPLICATIONS);

        $applications = $this->query->builder([], $user)->paginate($perPage);

        return $this->success(
            ApplicationListResource::collection($applications),
            'Applications retrieved successfully'
        );
    }

    public function show(
        #[CurrentUser] User $user,
        JobApplication $application
    ): JsonResponse {
        if ($application->user_id !== $user->id) {
            return $this->fail('Application not found', Response::HTTP_NOT_FOUND);
        }

        $application->load(['jobVacancy.company', 'jobVacancy.category', 'resume']);

        return $this->success(
            new JobApplicationResource($application),
            'Application details retrieved successfully'
        );
    }
}
