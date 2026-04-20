<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final readonly class CreateAvatarAction
{
    public function handle(User $user, UploadedFile $avatar): User
    {
        if ($user->profile->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $avatar->store('avatars', 'public');

        $user->profile()->update(['avatar' => $path]);

        return $user;
    }
}
