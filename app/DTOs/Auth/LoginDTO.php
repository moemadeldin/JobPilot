<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use SensitiveParameter;

final readonly class LoginDTO
{
    public function __construct(
        public string $email,
        #[SensitiveParameter] public string $password,
    ) {}

    /**
     * @param  array<string>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: (string) $data['email'],
            password: (string) $data['password'],
        );
    }
}
