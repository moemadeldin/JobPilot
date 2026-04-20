<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Models\JobVacancy;
use App\Models\User;
use App\Services\GenerateCoverLetterService;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class CoverLetterController
{
    use APIResponses;

    public function __construct(private GenerateCoverLetterService $service) {}

    public function __invoke(
        #[CurrentUser] User $user,
        JobVacancy $job,
    ): JsonResponse {

        if (empty($user->resume->extracted_text)) {
            return $this->fail('Resume has no extracted text', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $coverLetter = $this->service->generate(
            (string) $user->resume->extracted_text,
            (string) $job->description
        );

        return $this->success(['cover_letter' => $coverLetter], 'Cover letter generated successfully', Response::HTTP_CREATED);
    }
}
