<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('roles has many users', function (): void {

    $role = Role::factory()->create();

    $users = User::factory()->count(3)->create();
    $role->users()->attach($users->pluck('id'));

    expect($role->users)->toHaveCount(3);
    expect($role->users->first())->toBeInstanceOf(User::class);
});
