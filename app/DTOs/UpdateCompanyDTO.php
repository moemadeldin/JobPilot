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

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ? (is_string($data['name']) ? $data['name'] : null) : null,
            industry: $data['industry'] ? (is_string($data['industry']) ? $data['industry'] : null) : null,
            address: $data['address'] ? (is_string($data['address']) ? $data['address'] : null) : null,
            website: $data['website'] ? (is_string($data['website']) ? $data['website'] : null) : null,
            status: $data['status'] ? (is_string($data['status']) ? $data['status'] : null) : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
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
