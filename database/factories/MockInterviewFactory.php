<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MockInterviewStatus;
use App\Models\MockInterview;
use App\Models\User;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MockInterview>
 */
final class MockInterviewFactory extends Factory
{
    use RefreshOnCreate;

    protected $model = MockInterview::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => MockInterviewStatus::SUGGESTED->value,
        ];
    }
}
