<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class JobApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $feedback = is_string($this->feedback)
            ? json_decode($this->feedback, true)
            : $this->feedback;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'job_vacancy_id' => $this->job_vacancy_id,
            'resume_id' => $this->resume_id,
            'cover_letter' => $this->cover_letter,
            'status' => $this->status->label(),

            'evaluation' => [
                'compatibility_score' => $this->compatibility_score
                    ? (float) $this->compatibility_score
                    : null,
                'feedback' => [
                    'strengths' => $feedback['strengths'] ?? [],
                    'weaknesses' => $feedback['weaknesses'] ?? [],
                ],
                'improvement_suggestions' => $this->improvement_suggestions,
                'reviewed_at' => $this->reviewed_at?->toIso8601String(),
            ],
            // 'mock_interview_status' => $this->mock_interview_status->label(),
            // Timestamps
            'applied_at' => $this->applied_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),

        ];
    }
}
