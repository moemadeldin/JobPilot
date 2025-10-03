<?php

declare(strict_types=1);

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('profile belongs to user', function (): void {
    $user = User::factory()->create();
    $profile = Profile::factory()->for($user)->create();

    expect($profile->user)->toBeInstanceOf(User::class);
    expect($profile->user->id)->toBe($user->id);
});
