<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

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
use Illuminate\Http\Response;

final readonly class CustomApplicationController
{
    use APIResponses;

    public function __construct(
        private UserCustomApplicationQuery $query
    ) {}

    public function index(#[CurrentUser] User $user, Request $request)
    {
        $perPage = (int) $request->query('per_page', Constants::NUMBER_OF_PAGINATED_JOB_APPLICATIONS);

        $filters = $request->only(['status']);

        $applications = $this->query->builder($filters, $user)->paginate($perPage);

        return JobApplicationListResource::collection($applications);
    }

    public function show(
        #[CurrentUser] User $user,
        CustomJobApplication $customApplication
    ): JsonResponse {

        if ($customApplication->user_id !== $user->id) {
            return $this->fail('Application not found', Response::HTTP_NOT_FOUND);
        }

        $customApplication->load(['mockInterview']);

        return $this->success(
            new CustomJobApplicationResource($customApplication),
            'Application details retrieved successfully'
        );
    }
}
