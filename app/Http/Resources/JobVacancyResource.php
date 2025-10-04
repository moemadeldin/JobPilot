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
            'job' => [
                'id' => $this->id,
                'job_category_id' => $this->job_category_id,
                'company_id' => $this->company_id,
                'title' => $this->title,
                'description' => $this->description,
                'location' => $this->location,
                'expected_salary' => $this->expected_salary,
                'employment_type' => $this->employment_type->label(),
                'status' => $this->is_active->label(),
            ],
        ];
    }
}
