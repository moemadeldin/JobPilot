<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\TokenManager;

beforeEach(function (): void {
    $this->service = new TokenManager();
});

test('register access token', function (): void {

    $user = User::factory()->create();

    $token = $this->service->createAccessToken($user, 'register');

    expect($token)->toBeString()
        ->and($user->access_token)
        ->toBe($token);
});
test('login access token', function (): void {

    $user = User::factory()->create();

    $token = $this->service->createAccessToken($user, 'login');

    expect($token)->toBeString()
        ->and($user->access_token)
        ->toBe($token);
});
test('reset access token', function (): void {

    $user = User::factory()->create();

    $token = $this->service->createAccessToken($user, 'reset');

    expect($token)->toBeString()
        ->and($user->access_token)
        ->toBe($token);
});
test('email access token', function (): void {

    $user = User::factory()->create();

    $token = $this->service->createAccessToken($user, 'email');

    expect($token)->toBeString()
        ->and($user->access_token)
        ->toBe($token);
});
