<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class CreateCompanyDTO
{
    public function __construct(
        public string $name,
        public string $industry,
        public string $address,
        public string $website,
        public string $status,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        assert(is_string($data['name']));
        assert(is_string($data['industry']));
        assert(is_string($data['address']));
        assert(is_string($data['website']));
        assert(is_string($data['status']));

        return new self(
            name: $data['name'],
            industry: $data['industry'],
            address: $data['address'],
            website: $data['website'],
            status: $data['status'],
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
