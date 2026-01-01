<?php

declare(strict_types=1);

use App\Enums\Roles;
use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('getRouteKeyName returns slug', function (): void {
    $company = Company::factory()->create();

    expect($company->getRouteKeyName())->toBe('slug');
});
test('company belongs to owner', function (): void {
    $user = User::factory()->create();
    $ownerRole = Role::query()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($ownerRole->id);

    $company = Company::factory()->create([
        'user_id' => $user->id,
    ]);

    expect($company->owner)->toBeInstanceOf(User::class);
    expect($company->user_id)->toBe($user->id);
});

test('company has many vacancies', function (): void {
    $company = Company::factory()->create();

    $vacancies = JobVacancy::factory()->for($company)->count(3)->create();

    expect($company->vacancies->first())->toBeInstanceOf(JobVacancy::class);
    expect($company->vacancies)->toHaveCount(3);
});

test('companies scope loads owner relationship', function (): void {
    $user = User::factory()->create();
    $ownerRole = Role::query()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($ownerRole->id);

    $company = Company::factory()->create([
        'user_id' => $user->id,
    ]);

    $company = Company::companies()->first();

    expect($company->relationLoaded('owner'))->toBeTrue();

});

test('companies scope loads companies by owner only', function (): void {
    $user = User::factory()->create();
    $ownerRole = Role::query()->create(['name' => Roles::OWNER->value]);
    $user->roles()->attach($ownerRole->id);

    $company = Company::factory()->create([
        'user_id' => $user->id,
    ]);

    $company = Company::companiesByOwner($user)->first();

    expect($company->relationLoaded('owner'))->toBeTrue();

});
