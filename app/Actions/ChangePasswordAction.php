<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\ChangePasswordDTO;
use App\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class ChangePasswordAction
{
    public function handle(User $user, ChangePasswordDTO $dto): User
    {
        throw_unless(Hash::check($dto->currentPassword, $user->password), AuthException::class, 'The current password is incorrect.');

        $user->update(['password' => $dto->newPassword]);
        // $user->refresh();

        return $user;
    }
}
