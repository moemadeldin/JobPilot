<?php

declare(strict_types=1);

use App\Enums\Status;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

// Login Method

it('can login user', function (): void {
    $user = User::factory()->create([
        'email' => 'johndoe@gmail.com',
        'password' => 'password123456',
    ]);

    $response = $this->postJson(route('login.store'), [
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
    $response = $this->postJson(route('login.store'), [
        'email' => 'johndo@gmail.com',
        'password' => 'password12345',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['email']);
});
it('validates login fields', function (): void {
    $response = $this->postJson(route('login.store'), []);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonValidationErrors(['email', 'password']);
});
it('returns error when login fails', function (): void {
    $user = User::factory()->create([
        'email' => 'johndoe@gmail.com',
        'password' => 'correctpassword123',
    ]);

    $response = $this->postJson(route('login.store'), [
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
    $response = $this->getJson(route('me.show'));

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    $response->assertJson([
        'message' => 'Unauthenticated.',
    ]);
});
it('returns user details', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(route('me.show'));

    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            'authenticated',
            'user' => [
                'id',
                'username',
                'email',
                'status',
            ],
        ],
    ]);
    $response->assertJson([
        'data' => [
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'username' => $user->profile->username ?? null,
                'email' => $user->email,
                'status' => $user->is_active->label(),
            ],
        ],
    ]);
});
it('can log out', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(route('logout.destroy'));
    $response->assertNoContent();

    expect($user->tokens()->count())->toBe(0);
});
it('require authentication to log out', function (): void {

    $response = $this->deleteJson(route('logout.destroy'));

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);

});
