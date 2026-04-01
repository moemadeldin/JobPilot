<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\MockInterviewQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read MockInterviewQuestion $resource
 */
final class InterviewQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order' => $this->resource->order,
            'question' => $this->resource->question,
            'answer' => $this->resource->answer,
        ];
    }
}
