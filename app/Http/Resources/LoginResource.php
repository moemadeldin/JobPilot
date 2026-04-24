<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read User $resource
 */
final class LoginResource extends JsonResource
{
    public const array JSON_STRUCTURE = [
        'user' => [
            'id',
            'email',
        ],
        'access_token',
        'needs_resume',
    ];

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hasResume = Resume::query()
            ->where('user_id', $this->resource->id)
            ->exists();

        return [
            'user' => [
                'id' => $this->resource->id,
                'email' => $this->resource->email,
            ],
            'access_token' => $this->resource->access_token,
            'needs_resume' => ! $hasResume,
        ];
    }
}
