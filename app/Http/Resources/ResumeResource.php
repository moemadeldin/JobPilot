<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read JobVacancy $resource
 */
final class ResumeResource extends JsonResource
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
            'user_id' => $this->resource->user_id,
            'name' => $this->resource->name,
            'path' => $this->resource->path,
        ];
    }
}
