<?php

namespace App\Http\Resources;

use App\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
                $this->owner?->name ?? 'name',
            ],
            'company_details' => [
                'id' => $this->id,
                'name' => $this->name,
                'industry' => $this->industry,
                'address' => $this->address,
                'website' => $this->website,
                'is_active' => $this->is_active->label(),
            ]
        ];
    }
}
