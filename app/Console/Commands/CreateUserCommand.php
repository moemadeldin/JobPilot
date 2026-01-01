<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Roles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

final class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $user['username'] = $this->ask('Name of the new user');
        $user['email'] = $this->ask('Email of the new user');
        $user['password'] = $this->secret('Password of the new user');
        $roleName = $this->choice('Role of the new user', ['admin', 'owner', 'user'], 1);

        $validator = Validator::make($user, [
            'username' => ['required', 'string', 'unique:users,username'],
            'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
            'password' => ['required', Password::defaults()],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return;
        }

        $roleValue = match (mb_strtolower($roleName)) {
            'admin' => Roles::ADMIN->value,
            'owner' => Roles::OWNER->value,
            'user' => Roles::USER->value,
        };

        $role = Role::query()->where('name', $roleValue)->first();
        if (! $role) {
            $this->error('Role not found');

            return;
        }

        DB::transaction(function () use ($user, $role): void {
            $newUser = User::query()->create([
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
            ]);
            $newUser->roles()->attach($role->id);
        });

        $this->info('User '.$user['email'].' created successfully');
    }
}
