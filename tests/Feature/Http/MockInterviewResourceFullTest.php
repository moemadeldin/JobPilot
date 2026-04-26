<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Models\CustomJobApplication;
use App\Models\CustomJobVacancy;
use App\Models\MockInterview;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;

test('mock interview resource transforms correctly', function (): void {
    Http::fake([
        '*' => Http::response([
            'choices' => [[
                'message' => [
                    'content' => json_encode([
                        'questions' => [
                            ['question' => 'Q1', 'answer' => 'A1'],
                        ],
                    ]),
                ],
            ]],
        ]),
    ]);

    $user = User::factory()->create();
    $vacancy = CustomJobVacancy::factory()->create(['user_id' => $user->id]);
    $application = CustomJobApplication::factory()->create([
        'user_id' => $user->id,
        'custom_job_vacancy_id' => $vacancy->id,
    ]);
    MockInterview::factory()->create(['application_id' => $application->id]);

    Sanctum::actingAs($user);

    $response = $this->getJson(sprintf('/api/v1/custom-applications/%s/mock', $application->id));

    $response->assertOk();
});
