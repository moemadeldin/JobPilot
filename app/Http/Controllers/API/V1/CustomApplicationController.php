<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\CustomJobApplicationOwnershipRequest;
use App\Http\Resources\CustomJobApplicationResource;
use App\Http\Resources\JobApplicationListResource;
use App\Models\CustomJobApplication;
use App\Models\User;
use App\Queries\UserCustomApplicationQuery;
use App\Traits\APIResponses;
use App\Utilities\Constants;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final readonly class CustomApplicationController
{
    use APIResponses;

    public function __construct(
        private UserCustomApplicationQuery $query
    ) {}

    public function index(#[CurrentUser] User $user, Request $request): AnonymousResourceCollection
    {
        $perPage = (int) $request->query('per_page', Constants::NUMBER_OF_PAGINATED_JOB_APPLICATIONS);

        /** @var array{status?: string} $filters */
        $filters = $request->only(['status']);

        $applications = $this->query->builder($filters, $user)->paginate($perPage);

        return JobApplicationListResource::collection($applications);
    }

    public function show(
        CustomJobApplicationOwnershipRequest $request,
        CustomJobApplication $customApplication
    ): JsonResponse {
        $customApplication->load(['customJobVacancy', 'mockInterview']);

        return $this->success(
            new CustomJobApplicationResource($customApplication),
            'Application details retrieved successfully'
        );
    }
}
