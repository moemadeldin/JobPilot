<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Roles;
use App\Models\Role;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class RoleFactory extends Factory
{
    use RefreshOnCreate;

    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(array_map(fn ($role) => $role->value, Roles::cases())),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (): array => ['name' => Roles::ADMIN->value]);
    }

    public function owner(): static
    {
        return $this->state(fn (): array => ['name' => Roles::OWNER->value]);
    }
}
