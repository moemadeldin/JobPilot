<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('sends verification code for valid email', function (): void {
    $user = User::factory()->create();

    $response = $this->postJson(route('forgot.store'), [
        'email' => $user->email,
    ]);

    $response->assertOk();
});

it('validates email format for forgot password', function (): void {
    $response = $this->postJson(route('forgot.store'), [
        'email' => 'invalid-email',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('verifies code with valid code', function (): void {
    $user = User::factory()->create();
    $user->update([
        'verification_code' => '123456',
        'verification_code_expire_at' => now()->addMinutes(10),
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson(route('verify.code'), [
        'email' => $user->email,
        'code' => '123456',
    ]);

    $response->assertOk();
});

it('fails with invalid code', function (): void {
    $user = User::factory()->create();
    $user->update([
        'verification_code' => '123456',
        'verification_code_expire_at' => now()->addMinutes(10),
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson(route('verify.code'), [
        'email' => $user->email,
        'code' => '000000',
    ]);

    $response->assertStatus(422);
});

it('resets password successfully', function (): void {
    $user = User::factory()->create();
    $user->update([
        'verification_code' => '123456',
        'verification_code_expire_at' => now()->addMinutes(10),
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson(route('reset.password'), [
        'new_password' => 'NewPassword123',
        'new_password_confirmation' => 'NewPassword123',
    ]);

    $response->assertOk();

    $user->refresh();
    expect(password_verify('NewPassword123', $user->password))->toBeTrue();
});

it('validates new password requirements', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson(route('reset.password'), [
        'new_password' => 'weak',
        'new_password_confirmation' => 'weak',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['new_password']);
});

it('validates password confirmation matches', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson(route('reset.password'), [
        'new_password' => 'Password123',
        'new_password_confirmation' => 'DifferentPassword123',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['new_password']);
});

it('requires authentication to reset password', function (): void {
    $response = $this->postJson(route('reset.password'), [
        'new_password' => 'Password123',
        'new_password_confirmation' => 'Password123',
    ]);

    $response->assertStatus(401);
});