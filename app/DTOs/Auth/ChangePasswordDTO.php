<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use SensitiveParameter;

final readonly class ChangePasswordDTO
{
    public function __construct(
        #[SensitiveParameter] public string $currentPassword,
        #[SensitiveParameter] public string $newPassword,
    ) {}

    /**
     * @param  array{current_password: mixed, new_password: mixed}  $data
     */
    public static function fromArray(array $data): self
    {
        /** @var string $currentPassword */
        $currentPassword = $data['current_password'];
        /** @var string $newPassword */
        $newPassword = $data['new_password'];

        return new self(
            currentPassword: $currentPassword,
            newPassword: $newPassword,
        );
    }
}
