<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Http\UploadedFile;

final readonly class CreateResumeDTO
{
    public function __construct(
        public UploadedFile|string $path,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            path: $data['path'],
        );
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
        ];
    }
}
