<?php

declare(strict_types=1);

use App\Enums\Roles;
use App\Http\Middleware\EnsureUserIsOwner;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

test('guest cannot access owner routes', function (): void {

    Route::get('/test', fn (): Response => response(Response::HTTP_OK))
        ->middleware(EnsureUserIsOwner::class);

    $response = $this->get('/test');

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
});

test('owner can access owner routes', function (): void {

    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($role->id);

    Route::get('/test', fn (): Response => response(Response::HTTP_OK))
        ->middleware(EnsureUserIsOwner::class);

    Sanctum::actingAs($user, ['*']);

    $response = $this->get('/test');

    $response->assertOk();
});
test('admin can access owner routes', function (): void {

    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($role->id);

    Route::get('/test', fn (): Response => response(Response::HTTP_OK))
        ->middleware(EnsureUserIsOwner::class);

    Sanctum::actingAs($user, ['*']);

    $response = $this->get('/test');

    $response->assertOk();
});
test('user cannot access owner routes', function (): void {

    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => Roles::USER->value]);
    $user->roles()->attach($role->id);

    Route::get('/test', fn (): Response => response(Response::HTTP_OK))
        ->middleware(EnsureUserIsOwner::class);

    Sanctum::actingAs($user, ['*']);

    $response = $this->get('/test');

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});
