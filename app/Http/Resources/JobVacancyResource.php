<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $this->id,
            'category' => $this->category->name,
            'company' => $this->company->name,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'expected_salary' => $this->expected_salary,
            'employment_type' => $this->employment_type->label() ?? null,
            'status' => $this->status->label() ?? null,
            'responsibilities' => $this->responsibilities,
            'requirements' => $this->requirements,
            'skills_required' => $this->skills_required,
            'experience_years_min' => $this->experience_years_min,
            'experience_years_max' => $this->experience_years_max,
            'nice_to_have' => $this->nice_to_have,
        ];
    }
}
