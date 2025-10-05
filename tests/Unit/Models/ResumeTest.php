<?php

declare(strict_types=1);

use App\Models\JobApplication;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('resume belongs to user', function (): void {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    expect($resume->user)->toBeInstanceOf(User::class);
    expect($resume->user->id)->toBe($user->id);
});
test('resume has many applications', function (): void {

    $resume = Resume::factory()->create();
    $jobApplication = JobApplication::factory()->for($resume)->count(3)->create();

    expect($resume->applications->first())->toBeInstanceOf(JobApplication::class);
    expect($resume->applications)->toHaveCount(3);
});
