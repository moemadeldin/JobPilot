<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read JobApplication $resource
 */
final class ApplicationListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'job' => [
                'title' => $this->resource->jobVacancy?->title,
                'location' => $this->resource->jobVacancy?->location,
                'company' => [
                    'name' => $this->resource->jobVacancy?->company?->name,
                ],
            ],
            'status' => $this->resource->status->label(),
            'compatibility_score' => $this->resource->compatibility_score
                ? (float) $this->resource->compatibility_score
                : null,
            'reviewed_at' => $this->resource->reviewed_at?->toIso8601String(),
        ];
    }
}
