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
            'email',
            'avatar',
            'status',
            'name',
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
                'email' => $this->resource->email,
                'avatar' => $this->resource->profile?->avatar,
                'status' => $this->resource->status->label(),
                'name' => $this->resource->resume !== null ? $this->resource->resume->name : '',
                'resume' => $this->resource->resume?->path,
            ],
        ];
    }
}
