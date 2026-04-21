<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\MockInterview;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read MockInterview $resource
 */
final class MockInterviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'status' => $this->resource->status->value,
            'questions' => $this->resource->questions->map(fn ($q): array => [
                'question' => $q->question,
                'answer' => $q->answer,
                'order' => $q->order,
            ]),
            'created_at' => $this->resource->created_at,
        ];
    }
}
