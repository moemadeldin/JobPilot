<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\CustomJobVacancy\CreateCustomJobVacancyAction;
use App\Actions\CustomJobVacancy\DeleteCustomJobVacancyAction;
use App\DTOs\CreateCustomJobVacancyDTO;
use App\Enums\Messages\Auth\SuccessMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteCustomJobVacancyRequest;
use App\Http\Requests\StoreCustomJobVacancyRequest;
use App\Http\Resources\CustomJobVacancyResource;
use App\Models\CustomJobVacancy;
use App\Models\User;
use App\Traits\APIResponses;
use App\Utilities\Constants;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class CustomJobVacancyController extends Controller
{
    use APIResponses;

    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $vacancies = $user->customJobVacancies()
            ->latest()
            ->paginate(Constants::NUMBER_OF_PAGINATED_JOB_VACANCIES);

        return $this->success(CustomJobVacancyResource::collection($vacancies), '');
    }

    public function store(
        StoreCustomJobVacancyRequest $request,
        CreateCustomJobVacancyAction $action,
        #[CurrentUser] User $user
    ): JsonResponse {
        return $this->success(
            new CustomJobVacancyResource($action->handle(
                CreateCustomJobVacancyDTO::fromArray($request->validated()),
                $user
            )),
            SuccessMessages::JOB_VACANCY_CREATED->value,
            Response::HTTP_CREATED
        );
    }

    public function show(CustomJobVacancy $customJobVacancy, #[CurrentUser] User $user): JsonResponse
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
