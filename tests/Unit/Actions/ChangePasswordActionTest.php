<?php

declare(strict_types=1);

use App\Actions\ChangePasswordAction;
use App\DTOs\Auth\ChangePasswordDTO;
use App\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

describe('ChangePasswordAction', function (): void {
    it('changes password with correct old password', function (): void {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword123'),
        ]);

        $action = resolve(ChangePasswordAction::class);
        $dto = new ChangePasswordDTO(
            currentPassword: 'oldpassword123',
            newPassword: 'newpassword123',
        );

        $action->handle($user, $dto);

        $user->refresh();
        expect(Hash::check('newpassword123', $user->password))->toBeTrue();
    });

    it('throws exception with wrong current password', function (): void {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword123'),
        ]);

        $action = resolve(ChangePasswordAction::class);
        $dto = new ChangePasswordDTO(
            currentPassword: 'wrongpassword',
            newPassword: 'newpassword123',
        );

        $action->handle($user, $dto);
    })->throws(AuthException::class);
});
