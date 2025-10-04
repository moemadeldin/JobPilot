<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
                $this->owner->username,
            ],
            'company_details' => [
                'id' => $this->id,
                'name' => $this->name,
                'slug' => $this->slug,
                'industry' => $this->industry,
                'address' => $this->address,
                'website' => $this->website,
                'is_active' => $this->is_active->label(),
            ],
        ];
    }
}
