<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ApplicationAnalytic;
use App\Models\JobVacancy;
use App\Models\User;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
final class ApplicationAnalyticFactory extends Factory
{
    use RefreshOnCreate;

    protected $model = ApplicationAnalytic::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_vacancy_id' => JobVacancy::factory(),
            'activity_date' => $this->faker->date(),
        ];
    }
}
