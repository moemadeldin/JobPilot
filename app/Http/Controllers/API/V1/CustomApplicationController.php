<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\CustomJobVacancy\CreateCustomJobApplicationAction;
use App\DTOs\CreateCustomJobApplicationDTO;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Controllers\Controller;
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

final class CustomApplicationController extends Controller
{
    use APIResponses;
    public function __construct(
        private UserCustomApplicationQuery $query
    ) {}
    public function index(#[CurrentUser] User $user, Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', Constants::NUMBER_OF_PAGINATED_JOB_APPLICATIONS);

        $applications = $this->query->builder([], $user)->paginate($perPage);

        return $this->success(
            CustomJobApplicationResource::collection($applications),
            'Applications retrieved successfully'
        );
    }
    public function show(
        #[CurrentUser] User $user,
        CustomJobApplication $customApplication
    ): JsonResponse {


        if ($customApplication->user_id !== $user->id) {
            return $this->fail('Application not found', Response::HTTP_NOT_FOUND);
        }

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
        $application = $customJobVacancy->customJobApplications()
            ->where('user_id', $user->id)
            ->first();

        if ($application) {
            return $this->success(
                new CustomJobApplicationResource($application),
                'Application already exists.',
            );
        }

        return $this->success(
            new CustomJobApplicationResource($action->handle(
                CreateCustomJobApplicationDTO::fromArray($request->all()),
                $customJobVacancy,
                $user
            )),
            SuccessMessages::APPLICATION_SUBMITTED->value,
            Response::HTTP_CREATED
        );
    }
}
