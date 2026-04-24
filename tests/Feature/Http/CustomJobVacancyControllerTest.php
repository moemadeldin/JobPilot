<?php

declare(strict_types=1);

use App\Models\CustomJobVacancy;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

describe('CustomJobVacancyController', function (): void {
    it('can list custom job vacancies', function (): void {
        $user = User::factory()->create();
        CustomJobVacancy::factory()->count(3)->for($user)->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(route('custom-vacancies.index'));

        $response->assertOk();
    });

    it('can show a custom job vacancy', function (): void {
        $user = User::factory()->create();
        $vacancy = CustomJobVacancy::factory()->for($user)->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(route('custom-vacancies.show', $vacancy));

        $response->assertOk();
    });

    it('can delete a custom job vacancy', function (): void {
        $user = User::factory()->create();
        $vacancy = CustomJobVacancy::factory()->for($user)->create();

        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('custom-vacancies.destroy', $vacancy));

        $response->assertNoContent();
    });

    it('requires authentication to list vacancies', function (): void {
        $response = $this->getJson(route('custom-vacancies.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    });

    it('requires authentication to create vacancy', function (): void {
        $response = $this->postJson(route('custom-vacancies.store'), [
            'job_text' => 'Test job',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    });
});
