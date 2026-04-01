<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read JobVacancy $resource
 */
final class JobVacancyResource extends JsonResource
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
            'category' => $this->resource->category?->name,
            'company' => $this->resource->company?->name,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'location' => $this->resource->location,
            'expected_salary' => $this->resource->expected_salary,
            'employment_type' => $this->resource->employment_type->label(),
            'status' => $this->resource->status->label(),
            'responsibilities' => $this->resource->responsibilities,
            'requirements' => $this->resource->requirements,
            'skills_required' => $this->resource->skills_required,
            'experience_years_min' => $this->resource->experience_years_min,
            'experience_years_max' => $this->resource->experience_years_max,
            'nice_to_have' => $this->resource->nice_to_have,
        ];
    }
}
