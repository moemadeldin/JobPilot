<?php

declare(strict_types=1);

use App\Enums\Status;
use App\Models\CustomJobApplication;
use App\Models\Profile;
use App\Models\Resume;
use App\Models\User;
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

test('has custom job applications relationship', function (): void {
    $user = User::factory()->has(CustomJobApplication::factory()->count(3), 'customJobApplications')->create();
    expect($user->customJobApplications)->toHaveCount(3);
    expect($user->customJobApplications->first())->toBeInstanceOf(CustomJobApplication::class);
});

test('isActive return true for Active user', function (): void {
    $user = User::factory()->create([
        'status' => Status::ACTIVE->value,
    ]);

    expect($user->isActive())->toBeTrue();
});
