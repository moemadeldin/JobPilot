<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Http\UploadedFile;

final readonly class CreateResumeDTO
{
    public function __construct(
        public UploadedFile|string $path,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            path: $data['path'] instanceof UploadedFile ? $data['path'] : (is_string($data['path']) ? $data['path'] : ''),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'path' => $this->path,
        ];
    }
}
