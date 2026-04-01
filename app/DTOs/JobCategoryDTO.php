<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class JobCategoryDTO
{
    public function __construct(
        public ?string $name,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ? (is_string($data['name']) ? $data['name'] : null) : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
