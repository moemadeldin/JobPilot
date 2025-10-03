<?php

declare(strict_types=1);

use App\Enums\Roles;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\Profile;
use App\Models\Resume;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAnalytic;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('has a profile relationship', function (): void {
    $user = User::factory()->has(Profile::factory())->create();
    expect($user->profile)->toBeInstanceOf(Profile::class);
});

test('has a resume relationship', function (): void {
    $user = User::factory()->has(Resume::factory())->create();
    expect($user->resume)->toBeInstanceOf(Resume::class);
});

test('has a company relationship', function (): void {
    $user = User::factory()->has(Company::factory()->count(3))->create();
    expect($user->companies)->toHaveCount(3);
    expect($user->companies->first())->toBeInstanceOf(Company::class);

});
test('has an application relationship', function (): void {
    $user = User::factory()->has(JobApplication::factory()->count(3), 'applications')->create();
    expect($user->applications)->toHaveCount(3);
    expect($user->applications->first())->toBeInstanceOf(JobApplication::class);

});
test('has a user analytic relationship', function (): void {
    $user = User::factory()->has(UserAnalytic::factory()->count(3), 'analytics')->create();
    expect($user->analytics)->toHaveCount(3);
    expect($user->analytics->first())->toBeInstanceOf(UserAnalytic::class);

});
test('isAdmin return true for admin user', function (): void {
    $user = User::factory()->create();

    $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
    $user->roles()->attach($adminRole->id);
    expect($user->isAdmin())->toBeTrue();
});
test('isOwner return true for owner user', function (): void {
    $user = User::factory()->create();

    $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($ownerRole->id);
    expect($user->isOwner())->toBeTrue();
});

test('getUserByEmail return real email', function (): void {
    $user = User::factory()->create(['email' => 'example123@gmail.com']);

    $email = User::getUserByEmail('example123@gmail.com')->first();

    expect($email->id)->toBe($user->id);
});
