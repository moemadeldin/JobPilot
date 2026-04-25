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
    public const array JSON_STRUCTURE = [
        'id',
        'questions' => [
            'order',
            'question',
            'answer',
        ],
        'created_at',
    ];

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'questions' => $this->resource->questions->map(fn ($q): array => [
                'order' => $q->order,
                'question' => $q->question,
                'answer' => $q->answer,
            ]),
            'created_at' => $this->resource->created_at,
        ];
    }
}
