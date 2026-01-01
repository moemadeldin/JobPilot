<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
final class ProfileFactory extends Factory
{
    use RefreshOnCreate;

    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'avatar' => $this->faker->image(),
            'phone' => $this->faker->phoneNumber(),
            'country' => $this->faker->country(),
        ];
    }
}
