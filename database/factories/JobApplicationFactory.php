<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\JobApplicationStatus;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class JobApplicationFactory extends Factory
{
    use RefreshOnCreate;

    protected $model = JobApplication::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_vacancy_id' => JobVacancy::factory(),
            'status' => JobApplicationStatus::APPROVED->value,
        ];
    }
}
