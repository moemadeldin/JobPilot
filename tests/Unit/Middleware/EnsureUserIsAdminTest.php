<?php

declare(strict_types=1);

use App\Enums\Roles;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

test('guest cannot access admin routes', function (): void {

    Route::get('/test', fn (): Response => response(Response::HTTP_OK))
        ->middleware(EnsureUserIsAdmin::class);

    $response = $this->get('/test');

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
});

test('owner cannot access admin routes', function (): void {

    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($role->id);

    Route::get('/test', fn (): Response => response(Response::HTTP_OK))
        ->middleware(EnsureUserIsAdmin::class);

    Sanctum::actingAs($user, ['*']);

    $response = $this->get('/test');

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

test('user cannot access admin routes', function (): void {

    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => Roles::USER->value]);
    $user->roles()->attach($role->id);

    Route::get('/test', fn (): Response => response(Response::HTTP_OK))
        ->middleware(EnsureUserIsAdmin::class);

    Sanctum::actingAs($user, ['*']);

    $response = $this->get('/test');

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

test('admin can access admin routes', function (): void {

    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($role->id);

    Route::get('/test', fn (): Response => response(Response::HTTP_OK))
        ->middleware(EnsureUserIsAdmin::class);

    Sanctum::actingAs($user, ['*']);

    $response = $this->get('/test');

    $response->assertOk();
});
