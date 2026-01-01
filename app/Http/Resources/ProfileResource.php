<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
                'id' => $this->id,
                'username' => $this->user->username ?? null,
                'email' => $this->email,
                'status' => $this->status->label(),
            ],
        ];
    }
}
