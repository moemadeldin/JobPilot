<?php

declare(strict_types=1);

namespace App\DTOs;

use SensitiveParameter;

final readonly class ChangePasswordDTO
{
    public function __construct(
        #[SensitiveParameter] public string $currentPassword,
        #[SensitiveParameter] public string $newPassword,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            currentPassword: (string) $data['current_password'],
            newPassword: (string) $data['new_password'],
        );
    }
}
