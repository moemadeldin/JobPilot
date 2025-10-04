<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class UpdateCompanyDTO
{
    public function __construct(
        public ?string $name,
        public ?string $industry,
        public ?string $address,
        public ?string $website,
        public ?string $is_active,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            industry: $data['industry'],
            address: $data['address'],
            website: $data['website'],
            is_active: $data['is_active'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'industry' => $this->industry,
            'address' => $this->address,
            'website' => $this->website,
            'is_active' => $this->is_active,
        ];
    }
}
