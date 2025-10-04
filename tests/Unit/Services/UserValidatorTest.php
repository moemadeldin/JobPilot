<?php

declare(strict_types=1);

use App\Enums\Messages\Auth\ValidateMessages;
use App\Enums\Status;
use App\Exceptions\AuthException;
use App\Models\User;
use App\Services\UserValidator;
use Illuminate\Http\Response;

beforeEach(function (): void {
    $this->validator = new UserValidator();
});

test('user is not found', function (): void {

    $this->expectException(AuthException::class);
    $this->expectExceptionMessage(ValidateMessages::INVALID_CREDENTIALS->value);
    $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

    $this->validator->validateUser(null);
});
test('user is found', function (): void {
    $user = User::factory()->create();

    $this->validator->validateUser($user);

    expect(true)->toBeTrue();

});
test('user is not active', function (): void {

    $user = User::factory()->create(['is_active' => Status::INACTIVE]);

    $this->expectException(AuthException::class);
    $this->expectExceptionMessage(ValidateMessages::AUTH_ERROR->value);
    $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

    $this->validator->validateUserIsActive($user);
});
test('user is active', function (): void {

    $user = User::factory()->create(['is_active' => Status::ACTIVE]);

    $this->validator->validateUserIsActive($user);

    expect(true)->toBeTrue();

});
test('user passes wrong credentials', function (): void {

    $user = User::factory()->create(['password' => '0123456789Aa']);

    $this->expectException(AuthException::class);
    $this->expectExceptionMessage(ValidateMessages::INVALID_CREDENTIALS->value);
    $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

    $this->validator->validateUserCredentials($user, '0123456789');
});
test('user passes correct credentials', function (): void {

    $user = User::factory()->create(['password' => '0123456789Aa']);

    $this->validator->validateUserCredentials($user, '0123456789Aa');

    expect(true)->toBeTrue();

});
