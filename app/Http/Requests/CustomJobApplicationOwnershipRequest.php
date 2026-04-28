<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\CustomJobApplication;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;

final class CustomJobApplicationOwnershipRequest extends FormRequest
{
    public function authorize(
        #[CurrentUser()] User $user,
        #[RouteParameter('customApplication')] CustomJobApplication $customApplication
    ): bool {
        return $customApplication->user_id === $user->id;
    }
}
