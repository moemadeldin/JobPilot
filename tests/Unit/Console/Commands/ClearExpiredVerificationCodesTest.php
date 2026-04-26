<?php

declare(strict_types=1);

use App\Console\Commands\ClearExpiredVerificationCodes;
use App\Models\User;
use Illuminate\Support\Facades\Date;

it('clears expired verification codes', function (): void {
    User::factory()->create([
        'verification_code' => '123456',
        'verification_code_expire_at' => Date::now()->subHour(),
    ]);

    User::factory()->create([
        'verification_code' => '654321',
        'verification_code_expire_at' => Date::now()->addHour(),
    ]);

    $command = resolve(ClearExpiredVerificationCodes::class);
    $command->handle();

    $expiredUser = User::query()->whereNotNull('verification_code')
        ->where('verification_code', '123456')
        ->first();

    expect($expiredUser)->toBeNull();

    $validUser = User::query()->whereNotNull('verification_code')
        ->where('verification_code', '654321')
        ->first();

    expect($validUser)->not->toBeNull();
});

it('does nothing when no expired codes exist', function (): void {
    User::factory()->create([
        'verification_code' => '123456',
        'verification_code_expire_at' => Date::now()->addHour(),
    ]);

    $beforeCount = User::query()->count();

    $command = resolve(ClearExpiredVerificationCodes::class);
    $command->handle();

    expect(User::query()->count())->toBe($beforeCount);
});

it('handles users with no verification code', function (): void {
    User::factory()->create([
        'verification_code' => null,
        'verification_code_expire_at' => null,
    ]);

    $command = resolve(ClearExpiredVerificationCodes::class);
    $command->handle();

    expect(User::query()->whereNull('verification_code')->exists())->toBeTrue();
});
