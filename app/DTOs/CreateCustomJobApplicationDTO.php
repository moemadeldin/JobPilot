<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class CreateCustomJobApplicationDTO
{
    public function __construct(
        public ?string $cover_letter,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            cover_letter: isset($data['cover_letter']) && is_string($data['cover_letter']) ? $data['cover_letter'] : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'cover_letter' => $this->cover_letter,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
