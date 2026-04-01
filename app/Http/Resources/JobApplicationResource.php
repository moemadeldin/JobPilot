<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read JobApplication $resource
 */
final class JobApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $feedback = is_string($this->resource->feedback)
            ? json_decode($this->resource->feedback, true)
            : $this->resource->feedback;

        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'job_vacancy_id' => $this->resource->job_vacancy_id,
            'resume_id' => $this->resource->resume_id,
            'cover_letter' => $this->resource->cover_letter,
            'status' => $this->resource->status->label(),

            'evaluation' => [
                'compatibility_score' => $this->resource->compatibility_score
                    ? (float) $this->resource->compatibility_score
                    : null,
                'feedback' => [
                    'strengths' => is_array($feedback) ? ($feedback['strengths'] ?? []) : [],
                    'weaknesses' => is_array($feedback) ? ($feedback['weaknesses'] ?? []) : [],
                ],
                'improvement_suggestions' => $this->resource->improvement_suggestions,
                'reviewed_at' => $this->resource->reviewed_at?->toIso8601String(),
            ],
            // 'mock_interview_status' => $this->resource->mock_interview_status->label(),
            // Timestamps
            'applied_at' => $this->resource->applied_at?->toIso8601String(),
            'created_at' => $this->resource->created_at?->toIso8601String(),
            'updated_at' => $this->resource->updated_at?->toIso8601String(),

        ];
    }
}
