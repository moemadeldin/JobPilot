<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class DeleteAccountAction
{
    public function handle(User $user, string $password): void
    {
        throw_unless(Hash::check($password, $user->password), AuthException::class, 'The password is incorrect.');

        $user->tokens()->delete();
        $user->delete();
    }
}
