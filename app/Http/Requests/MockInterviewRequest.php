<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;

final class MockInterviewRequest extends FormRequest
{
    public function authorize(
        #[CurrentUser()] User $user,
        #[RouteParameter('application')] JobApplication $application
    ): bool {
        return $application->user_id === $user->id;
    }
}
