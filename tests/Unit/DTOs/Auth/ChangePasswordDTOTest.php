<?php

declare(strict_types=1);

use App\DTOs\Auth\ChangePasswordDTO;

describe('ChangePasswordDTO', function (): void {
    it('creates from array', function (): void {
        $dto = ChangePasswordDTO::fromArray([
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword',
        ]);

        expect($dto->currentPassword)->toBe('oldpassword');
        expect($dto->newPassword)->toBe('newpassword');
    });

    it('creates with constructor', function (): void {
        $dto = new ChangePasswordDTO(
            currentPassword: 'oldpassword',
            newPassword: 'newpassword',
        );

        expect($dto->currentPassword)->toBe('oldpassword');
        expect($dto->newPassword)->toBe('newpassword');
    });
});
