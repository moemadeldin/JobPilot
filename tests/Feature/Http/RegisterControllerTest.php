<?php

use App\Models\User;
use Illuminate\Http\Response;

it('can register a user', function (): void {
    $response = $this->postJson(route('register.store'), [
        'email' => 'johndoe@gmail.com',
        'password' => 'password123456',
        'password_confirmation' => 'password123456',
    ]);

    $response->assertStatus(Response::HTTP_CREATED);

    $user = User::getUserByEmail('johndoe@gmail.com')->first();

    expect($user)
        ->not->toBeNull()
        ->and($user->email)->toBe('johndoe@gmail.com')
        ->and($user->email_verified_at)->toBeNull();
});

it('validates request fields', function (): void {
    $response = $this->postJson(route('register.store'), []);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['email', 'password']);
});
it('validates email format', function (): void {
    $response = $this->postJson(route('register.store'), [
        'email' => 'johndoe',
        'password' => 'password123456',
        'password_confirmation' => 'password123456',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['email']);
});
it('validates email format is actual email', function (): void {
    $response = $this->postJson(route('register.store'), [
        'email' => 'johndoe@example.com',
        'password' => 'password123456',
        'password_confirmation' => 'password123456',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['email']);
});
it('validates email is unique', function (): void {
    $user = User::factory()->create([
        'email' => 'johndoe@gmail.com',
    ]);

    $response = $this->postJson(route('register.store'), [
        'email' => 'johndoe@gmail.com',
        'password' => 'password123456',
        'password_confirmation' => 'password123456',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['email']);
});
it('validates password rules', function (): void {

    $response = $this->postJson(route('register.store'), [
        'email' => 'johndoe@gmail.com',
        'password' => '0123456',
        'password_confirmation' => '0123456',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['password']);
});
it('validates password confirmation', function (): void {

    $response = $this->postJson(route('register.store'), [
        'email' => 'johndoe@gmail.com',
        'password' => 'password123456',
        'password_confirmation' => 'password12345',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['password']);
});
