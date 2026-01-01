<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->create([
            'email' => 'admin@gmail.com',
            'password' => '0123456789Aa',
        ]);
        $adminRole = Role::factory()->create(['name' => Roles::ADMIN->value]);
        $admin->roles()->attach($adminRole->id);

        $owner = User::query()->create([
            'email' => 'owner@gmail.com',
            'password' => '0123456789Aa',
        ]);
        $ownerRole = Role::factory()->create(['name' => Roles::OWNER->value]);
        $owner->roles()->attach($ownerRole->id);

        $user = User::query()->create([
            'email' => 'user@gmail.com',
            'password' => '0123456789Aa',
        ]);
        $userRole = Role::factory()->create(['name' => Roles::USER->value]);
        $user->roles()->attach($userRole->id);
    }
}
