<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class UpdateProfileDTO
{
    public function __construct(
        public ?string $email,
        public ?string $phone,
        public ?string $country,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: array_key_exists('email', $data) ? (string) $data['email'] : null,
            phone: array_key_exists('phone', $data) ? (string) $data['phone'] : null,
            country: array_key_exists('country', $data) ? (string) $data['country'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
        ];
    }
}
