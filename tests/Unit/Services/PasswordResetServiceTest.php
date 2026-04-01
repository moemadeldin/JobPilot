<?php

declare(strict_types=1);

use App\Enums\Status;
use App\Events\PasswordVerificationCodeSent;
use App\Interfaces\Auth\TokenManagerInterface;
use App\Models\User;
use App\Services\PasswordResetService;
use App\Services\UserValidator;
use Illuminate\Support\Facades\Event;
use Mockery;

beforeEach(function (): void {
    $this->tokenManager = Mockery::mock(TokenManagerInterface::class);
    $this->userValidator = new UserValidator();
    $this->service = new PasswordResetService($this->tokenManager, $this->userValidator);
});

test('forgot sends verification code and creates token', function (): void {
    Event::fake(PasswordVerificationCodeSent::class);

    $this->tokenManager->shouldReceive('createAccessToken')
        ->once()
        ->andReturn('fake-token');

    $user = User::factory()->create(['status' => Status::ACTIVE]);

    $result = $this->service->forgot($user->email);

    expect($result->id)->toBe($user->id);
    expect($result->verification_code)->not->toBeNull();
    expect($result->verification_code_expire_at)->not->toBeNull();

    Event::assertDispatched(PasswordVerificationCodeSent::class);
});

test('checkCode validates code and creates token', function (): void {
    $this->tokenManager->shouldReceive('createAccessToken')
        ->once()
        ->andReturn('fake-token');

    $user = User::factory()->create([
        'status' => Status::ACTIVE,
        'verification_code' => 123456,
    ]);

    $result = $this->service->checkCode($user->email, '123456');

    expect($result->id)->toBe($user->id);
});

test('reset updates password and clears verification code', function (): void {
    $this->tokenManager->shouldReceive('deleteAccessToken')
        ->once();

    $user = User::factory()->create([
        'status' => Status::ACTIVE,
        'password' => 'oldpassword',
    ]);

    $user->createToken('test-token');

    $result = $this->service->reset($user, 'newpassword');

    expect($result->verification_code)->toBeNull();
    expect($result->verification_code_expire_at)->toBeNull();
});
