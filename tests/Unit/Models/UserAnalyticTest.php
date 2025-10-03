<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\UserAnalytic;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user analytic belongs to user', function (): void {
    $user = User::factory()->create();
    $userAnalytic = UserAnalytic::factory()->for($user)->create();

    expect($userAnalytic->user)->toBeInstanceOf(User::class);
    expect($userAnalytic->user_id)->toBe($user->id);
});
