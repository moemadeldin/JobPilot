<?php

declare(strict_types=1);

use App\Enums\Roles;
use App\Models\JobCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

it('can create a category', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('admin.categories.store'), [
        'name' => 'Tech',
    ]);

    $response->assertStatus(Response::HTTP_CREATED);

});
it('can update a category', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);
    $category = JobCategory::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(route('admin.categories.update', $category), [
        'name' => 'Tech',
    ]);

    $response->assertOk();

    $category->refresh();
    expect($category->name)->toBe('Tech');

});
it('can delete a category', function (): void {
    $user = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);
    $category = JobCategory::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(route('admin.categories.destroy', $category));

    $response->assertNoContent();

});

it('owner or user can create a category', function (): void {
    $user = User::factory()->create();
    $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($ownerRole->id);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('admin.categories.store'), [
        'name' => 'Tech',
    ]);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});
