<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\CustomJobVacancy\CreateCustomJobApplicationAction;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Resources\CustomJobApplicationResource;
use App\Models\CustomJobApplication;
use App\Models\CustomJobVacancy;
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

        return CustomJobApplicationResource::collection($applications);
    }

    public function show(
        #[CurrentUser] User $user,
        CustomJobApplication $customApplication
    ): JsonResponse {

        if ($customApplication->user_id !== $user->id) {
            return $this->fail('Application not found', Response::HTTP_NOT_FOUND);
        }

        $customApplication->load('mockInterview');

        return $this->success(
            new CustomJobApplicationResource($customApplication),
            'Application details retrieved successfully'
        );
    }

    public function store(
        Request $request,
        CreateCustomJobApplicationAction $action,
        CustomJobVacancy $customJobVacancy,
        #[CurrentUser] User $user
    ): JsonResponse {
        /** @var string|null $coverLetter */
        $coverLetter = $request->input('cover_letter');

        return $this->success(
            new CustomJobApplicationResource($action->handle(
                $coverLetter,
                $customJobVacancy,
                $user
            )),
            SuccessMessages::APPLICATION_SUBMITTED->value,
            Response::HTTP_CREATED
        );
    }
}
