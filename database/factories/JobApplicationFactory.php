<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\JobApplicationStatus;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
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
            'resume_id' => Resume::factory(),
            'cover_letter' => $this->faker->text(200),
            'compatibility_score' => $this->faker->numberBetween(0, 100),
            'feedback' => $this->faker->randomElement([
                ['rating' => 5, 'comment' => 'Excellent work!'],
                ['rating' => 4, 'comment' => 'Very good performance.'],
                ['rating' => 3, 'comment' => 'Satisfactory.'],
                ['rating' => 2, 'comment' => 'Needs improvement.'],
                ['rating' => 1, 'comment' => 'Unacceptable.'],
            ]),
            'improvement_suggestions' => $this->faker->text(200),
            'applied_at' => $this->faker->date(),
            'reviewed_at' => $this->faker->date(),
        ];
    }
}
