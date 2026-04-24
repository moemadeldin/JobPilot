<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\CustomJobApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read CustomJobApplication $resource
 */
final class CustomJobApplicationResource extends JsonResource
{
    public const array JSON_STRUCTURE = [
        'id',
        'custom_job_vacancy_id',
        'custom_job_vacancy_title',
        'custom_job_vacancy_company',
        'compatibility_score',
        'feedback',
        'improvement_suggestions',
        'cover_letter',
        'created_at',
        'updated_at',
    ];

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'custom_job_vacancy_id' => $this->resource->custom_job_vacancy_id,
            'custom_job_vacancy_title' => $this->resource->customJobVacancy->title,
            'custom_job_vacancy_company' => $this->resource->customJobVacancy->company,
            'compatibility_score' => $this->resource->compatibility_score,
            'feedback' => $this->resource->feedback,
            'improvement_suggestions' => $this->resource->improvement_suggestions,
            'mock_interview_status' => $this->whenLoaded('mockInterview', fn () => $this->resource->mockInterview->status->label()),
            'cover_letter' => $this->resource->cover_letter,
            'created_at' => $this->resource->created_at?->toIso8601String(),
            'updated_at' => $this->resource->updated_at?->toIso8601String(),
        ];
    }
}
