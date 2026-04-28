<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CustomJobVacancyWithResultsResource extends JsonResource
{
    public const array JSON_STRUCTURE = [
        'vacancy',
        'application',
        'mock_interview',
    ];
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'vacancy' => new CustomJobVacancyResource($this['vacancy']),
            'application' => new CustomJobApplicationResource($this['application']),
            'mock_interview' => $this['mock_interview']
                ? new MockInterviewResource($this['mock_interview'])
                : null,
        ];
    }
}
