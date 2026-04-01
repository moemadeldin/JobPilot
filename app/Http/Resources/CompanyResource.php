<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Company $resource
 */
final class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'owner' => [
                $this->resource->owner->username ?? null,
            ],
            'company_details' => [
                'id' => $this->resource->id,
                'name' => $this->resource->name,
                'slug' => $this->resource->slug,
                'industry' => $this->resource->industry,
                'address' => $this->resource->address,
                'website' => $this->resource->website,
                'status' => $this->resource->status->label(),
            ],
        ];
    }
}
