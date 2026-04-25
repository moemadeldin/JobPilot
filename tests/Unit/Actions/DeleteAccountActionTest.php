<?php

declare(strict_types=1);

use App\Actions\DeleteAccountAction;
use App\Exceptions\AuthException;
use App\Models\User;

describe('DeleteAccountAction', function (): void {
    it('deletes user account with correct password', function (): void {
        $user = User::factory()->create([
            'password' => bcrypt('password123456'),
        ]);

        $action = resolve(DeleteAccountAction::class);
        $action->handle($user, 'password123456');

        expect(User::query()->find($user->id))->toBeNull();
    });

    it('throws exception with wrong password', function (): void {
        $user = User::factory()->create([
            'password' => bcrypt('password123456'),
        ]);

        $action = resolve(DeleteAccountAction::class);
        $action->handle($user, 'wrongpassword');
    })->throws(AuthException::class);
});
