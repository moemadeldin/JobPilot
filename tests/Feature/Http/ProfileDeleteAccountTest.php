<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('can delete account with correct password', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->deleteJson('/api/v1/profile', [
        'password' => 'password',
    ]);

    $response->assertNoContent();
});
