<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Models\CustomJobApplication;
use App\Models\MockInterview;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('can get mock interview questions', function (): void {
    $user = User::factory()->create();
    $application = CustomJobApplication::factory()->create([
        'user_id' => $user->id,
    ]);
    MockInterview::factory()->create([
        'application_id' => $application->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson(sprintf('/api/v1/custom-applications/%s/mock', $application->id));

    $response->assertOk();
});
