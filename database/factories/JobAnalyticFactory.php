<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\JobAnalytic;
use App\Models\JobVacancy;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobAnalytic>
 */
final class JobAnalyticFactory extends Factory
{
    /**
     * @use RefreshOnCreate<JobAnalytic>
     */
    use RefreshOnCreate;

    /**
     * @var class-string<JobAnalytic>
     */
    protected $model = JobAnalytic::class;

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
