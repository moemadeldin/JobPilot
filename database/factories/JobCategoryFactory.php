<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\JobCategory;
use App\Models\User;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
final class JobCategoryFactory extends Factory
{
    use RefreshOnCreate;

    protected $model = JobCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
