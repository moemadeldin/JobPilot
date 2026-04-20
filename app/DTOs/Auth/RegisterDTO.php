<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use SensitiveParameter;

final readonly class RegisterDTO
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $phone,
        public string $country,
        public string $email,
        #[SensitiveParameter] public string $password,
    ) {}

    /**
     * @param  array<string>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            firstName: $data['first_name'],
            lastName: $data['last_name'],
            phone: $data['phone'],
            country: $data['country'],
            email: $data['email'],
            password: $data['password'],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'country' => $this->country,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
