<?php

declare(strict_types=1);

use App\Enums\Status;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

// Register Method

it('can register a user', function (): void {
    $response = $this->postJson(route('register.post'), [
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
    $response = $this->postJson(route('register.post'), []);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['email', 'password']);
});
it('validates email format', function (): void {
    $response = $this->postJson(route('register.post'), [
        'email' => 'johndoe',
        'password' => 'password123456',
        'password_confirmation' => 'password123456',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['email']);
});
it('validates email format is actual email', function (): void {
    $response = $this->postJson(route('register.post'), [
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

    $response = $this->postJson(route('register.post'), [
        'email' => 'johndoe@gmail.com',
        'password' => 'password123456',
        'password_confirmation' => 'password123456',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['email']);
});
it('validates password rules', function (): void {

    $response = $this->postJson(route('register.post'), [
        'email' => 'johndoe@gmail.com',
        'password' => '0123456',
        'password_confirmation' => '0123456',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['password']);
});
it('validates password confirmation', function (): void {

    $response = $this->postJson(route('register.post'), [
        'email' => 'johndoe@gmail.com',
        'password' => 'password123456',
        'password_confirmation' => 'password12345',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['password']);
});

// Login Method

it('can login user', function (): void {
    $user = User::factory()->create([
        'email' => 'johndoe@gmail.com',
        'password' => 'password123456',
    ]);

    $response = $this->postJson(route('login.post'), [
        'email' => 'johndoe@gmail.com',
        'password' => 'password123456',
    ]);
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            'user' => [
                'id',
                'email',
            ],
            'access_token',
        ],
    ]);
    $response->assertJson([
        'data' => [
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
        ],
    ]);
    expect($response->json('data.access_token'))->toBeString();
});

it('validates login credentials', function (): void {
    $response = $this->postJson(route('login.post'), [
        'email' => 'johndo@gmail.com',
        'password' => 'password12345',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['email']);
});
it('validates login fields', function (): void {
    $response = $this->postJson(route('login.post'), []);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['email', 'password']);
});
it('returns error when login fails', function (): void {
    $user = User::factory()->create([
        'email' => 'johndoe@gmail.com',
        'password' => 'correctpassword123',
    ]);

    $response = $this->postJson(route('login.post'), [
        'email' => 'johndoe@gmail.com',
        'password' => 'wrongpassword123',
        'is_active' => Status::BLOCKED->value,
    ]);

    if (! $user->isActive()) {
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
    $response->assertStatus(Response::HTTP_BAD_REQUEST);

});
it('returns unauthenticated when not logged in', function (): void {
    $response = $this->getJson(route('me'));

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    $response->assertJson([
        'message' => 'Unauthenticated.',
    ]);
});
it('returns user details', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(route('me'));

    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            'username',
            'email',
            'status',
        ],
    ]);
    $response->assertJson([
        'data' => [
            'username' => $user->profile->username ?? null,
            'email' => $user->email,
            'status' => $user->is_active->label(),
        ],
    ]);
});
it('can log out', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('logout.post'));
    $response->assertNoContent();

    expect($user->tokens()->count())->toBe(0);
});
it('require authentication to log out', function (): void {

    $response = $this->postJson(route('logout.post'));

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);

});
