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
        public ?string $status,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            industry: $data['industry'],
            address: $data['address'],
            website: $data['website'],
            status: $data['status'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'industry' => $this->industry,
            'address' => $this->address,
            'website' => $this->website,
            'status' => $this->status,
        ];
    }
}
