<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read User $resource
 */
final class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'authenticated' => true,
            'user' => [
                'id' => $this->resource->id,
                'first_name' => $this->resource->first_name,
                'last_name' => $this->resource->last_name,
                'email' => $this->resource->email,
                'avatar' => $this->resource->avatar,
                'phone' => $this->resource->phone,
                'country' => $this->resource->country,
                'status' => $this->resource->status->label(),
                'resume' => $this->resource->resume->path
            ],
        ];
    }
}
