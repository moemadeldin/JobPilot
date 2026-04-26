<?php

declare(strict_types=1);

use App\Console\Commands\CreateUserCommand;

test('command signature', function (): void {
    $command = new CreateUserCommand();
    expect($command->getName())->toBe('users:create');
});

test('command description', function (): void {
    $command = new CreateUserCommand();
    expect($command->getDescription())->toBe('Creates a new user');
});
