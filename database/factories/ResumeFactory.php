<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Resume;
use App\Models\User;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class ResumeFactory extends Factory
{
    use RefreshOnCreate;

    protected $model = Resume::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name,
        ];
    }
}
