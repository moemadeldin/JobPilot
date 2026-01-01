<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\TokenManager;
use App\Utilities\Constants;

beforeEach(function (): void {
    $this->service = new TokenManager();
});

test('register access token', function (): void {

    $user = User::factory()->create();

    $token = $this->service->createAccessToken($user, Constants::REGISTER_TOKEN_TYPE);

    expect($token)->toBeString()
        ->and($user->access_token)
        ->toBe($token);
});
test('login access token', function (): void {

    $user = User::factory()->create();

    $token = $this->service->createAccessToken($user, Constants::LOGIN_TOKEN_TYPE);

    expect($token)->toBeString()
        ->and($user->access_token)
        ->toBe($token);
});
test('reset access token', function (): void {

    $user = User::factory()->create();

    $token = $this->service->createAccessToken($user, Constants::PASSWORD_RESET_TOKEN_TYPE);

    expect($token)->toBeString()
        ->and($user->access_token)
        ->toBe($token);
});
test('email access token', function (): void {

    $user = User::factory()->create();

    $token = $this->service->createAccessToken($user, Constants::EMAIL_VERIFICATION_TOKEN_TYPE);

    expect($token)->toBeString()
        ->and($user->access_token)
        ->toBe($token);
});
