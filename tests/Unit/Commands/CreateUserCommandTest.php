<?php

declare(strict_types=1);

use App\Console\Commands\CreateUserCommand;
use App\Enums\Roles;
use App\Models\Role;
use App\Models\User;

test('creates a new admin user', function (): void {

    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);

    $this->artisan(CreateUserCommand::class)
        ->expectsQuestion('Name of the new user', 'john doe')
        ->expectsQuestion('Email of the new user', 'johndoe@gmail.com')
        ->expectsQuestion('Password of the new user', '0123456789Aa')
        ->expectsChoice('Role of the new user', 'admin', ['admin', 'owner', 'user'])
        ->expectsOutput('User johndoe@gmail.com created successfully')
        ->assertExitCode(0);

    $this->assertDatabaseHas('users', [
        'username' => 'john doe',
        'email' => 'johndoe@gmail.com',
    ]);

    $user = User::where('email', 'johndoe@gmail.com')->first();
    expect($user->roles->pluck('name'))->toContain(Roles::ADMIN);
});

test('creates a new owner user', function (): void {

    $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);

    $this->artisan(CreateUserCommand::class)
        ->expectsQuestion('Name of the new user', 'john doe')
        ->expectsQuestion('Email of the new user', 'johndoe@gmail.com')
        ->expectsQuestion('Password of the new user', '0123456789Aa')
        ->expectsChoice('Role of the new user', 'owner', ['admin', 'owner', 'user'])
        ->expectsOutput('User johndoe@gmail.com created successfully')
        ->assertExitCode(0);

    $this->assertDatabaseHas('users', [
        'username' => 'john doe',
        'email' => 'johndoe@gmail.com',
    ]);

    $user = User::where('email', 'johndoe@gmail.com')->first();
    expect($user->roles->pluck('name'))->toContain(Roles::OWNER);
});
test('creates a new user ', function (): void {

    $userRole = Role::factory()->create(['name' => Roles::USER->value]);

    $this->artisan(CreateUserCommand::class)
        ->expectsQuestion('Name of the new user', 'john doe')
        ->expectsQuestion('Email of the new user', 'johndoe@gmail.com')
        ->expectsQuestion('Password of the new user', '0123456789Aa')
        ->expectsChoice('Role of the new user', 'user', ['admin', 'owner', 'user'])
        ->expectsOutput('User johndoe@gmail.com created successfully')
        ->assertExitCode(0);

    $this->assertDatabaseHas('users', [
        'username' => 'john doe',
        'email' => 'johndoe@gmail.com',
    ]);

    $user = User::where('email', 'johndoe@gmail.com')->first();
    expect($user->roles->pluck('name'))->toContain(Roles::USER);
});

test('fails validation with invalid email', function (): void {
    Role::factory()->create(['name' => Roles::ADMIN->value]);

    $this->artisan(CreateUserCommand::class)
        ->expectsQuestion('Name of the new user', 'john doe')
        ->expectsQuestion('Email of the new user', 'invalid-email')
        ->expectsQuestion('Password of the new user', '0123456789Aa')
        ->expectsChoice('Role of the new user', 'admin', ['admin', 'owner', 'user'])
        ->expectsOutput('The email field must be a valid email address.')
        ->assertExitCode(0);

    $this->assertDatabaseMissing('users', ['email' => 'invalid-email']);
});
test('fails when role does not exist', function (): void {

    $this->artisan(CreateUserCommand::class)
        ->expectsQuestion('Name of the new user', 'john doe')
        ->expectsQuestion('Email of the new user', 'johndoe@gmail.com')
        ->expectsQuestion('Password of the new user', '0123456789Aa')
        ->expectsChoice('Role of the new user', 'admin', ['admin', 'owner', 'user'])
        ->expectsOutput('Role not found')
        ->assertExitCode(0);

    $this->assertDatabaseMissing('users', ['email' => 'johndoe@gmail.com']);
});
