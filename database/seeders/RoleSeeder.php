<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'name' => Roles::ADMIN->value,
        ]);
        Role::create([
            'name' => Roles::OWNER->value,
        ]);
        Role::create([
            'name' => Roles::USER->value,
        ]);
    }
}
