<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Models\User;

test('it change password', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/v1/profile/password', [
            'current_password' => 'password',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

    $response->assertOk();
});
