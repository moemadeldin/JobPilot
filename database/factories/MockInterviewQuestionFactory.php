<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\JobApplication;
use App\Models\MockInterviewQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MockInterviewQuestion>
 */
final class MockInterviewQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jobApplication = JobApplication::factory()->create();

        return [
            'job_application_id' => $jobApplication->id,
            'question' => fake()->sentence().'?',
            'answer' => fake()->paragraph(),
            'order' => 1,
        ];
    }
}
