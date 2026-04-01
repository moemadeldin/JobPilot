<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\JobCategory;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<JobCategory>
 */
final class JobCategoryFactory extends Factory
{
    /**
     * @use RefreshOnCreate<JobCategory>
     */
    use RefreshOnCreate;

    /**
     * @var class-string<JobCategory>
     */
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
