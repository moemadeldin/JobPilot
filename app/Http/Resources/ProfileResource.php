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
    public const array JSON_STRUCTURE = [
        'authenticated',
        'user' => [
            'id',
            'full_name',
            'first_name',
            'last_name',
            'email',
            'avatar',
            'phone',
            'country',
            'status',
            'resume',
            ],
    ];
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
                'full_name' => $this->resource->profile?->fullName,
                'first_name' => $this->resource->profile?->first_name,
                'last_name' => $this->resource->profile?->last_name,
                'email' => $this->resource->email,
                'avatar' => $this->resource->profile?->avatar,
                'phone' => $this->resource->profile?->phone,
                'country' => $this->resource->profile?->country,
                'status' => $this->resource->status->label(),
                'resume' => $this->resource->resume?->path,
            ],
        ];
    }
}
