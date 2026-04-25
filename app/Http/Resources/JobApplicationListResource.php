<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\CustomJobApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read CustomJobApplication $resource
 */
final class JobApplicationListResource extends JsonResource
{
    public const array JSON_STRUCTURE = [
        'id',
        'custom_job_vacancy_id',
        'custom_job_vacancy_title',
        'custom_job_vacancy_company',
        'compatibility_score',
        'mock_interview_status',
        'created_at',
        'updated_at',
    ];

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'custom_job_vacancy_id' => $this->resource->custom_job_vacancy_id,
            'custom_job_vacancy_title' => $this->resource->customJobVacancy->title,
            'custom_job_vacancy_company' => $this->resource->customJobVacancy->company,
            'compatibility_score' => $this->resource->compatibility_score,
            'mock_interview_status' => $this->whenLoaded('mockInterview', fn (): string => $this->resource->mockInterview?->status?->label() ?? ''),
            'created_at' => $this->resource->created_at?->toIso8601String(),
            'updated_at' => $this->resource->updated_at?->toIso8601String(),
        ];
    }
}
