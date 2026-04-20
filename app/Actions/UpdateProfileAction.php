<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\UpdateProfileDTO;
use App\Models\User;

final readonly class UpdateProfileAction
{
    public function handle(User $user, UpdateProfileDTO $dto): User
    {
        if ($dto->email) {
            $user->update(['email' => $dto->email]);
        }
        $user->profile()->update([
            'first_name' => $dto->firstName,
            'last_name' => $dto->lastName,
            'phone' => $dto->phone,
            'country' => $dto->country,
        ]);
        return $user;
    }
}
