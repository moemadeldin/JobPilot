<?php

declare(strict_types=1);

use App\Models\CustomJobApplication;
use App\Models\CustomJobVacancy;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

describe('CustomApplicationController', function (): void {
    it('can list custom job applications', function (): void {
        $user = User::factory()->create();
        $vacancy = CustomJobVacancy::factory()->for($user)->create();
        CustomJobApplication::factory()->count(3)->for($user)->for($vacancy)->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(route('custom-applications.index'));

        $response->assertOk();
    });

    it('can show a custom job application', function (): void {
        $user = User::factory()->create();
        $vacancy = CustomJobVacancy::factory()->for($user)->create();
        $application = CustomJobApplication::factory()->for($user)->for($vacancy)->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/custom-applications/'.$application->id);

        $response->assertOk();
    });

    it('returns 404 for other user application', function (): void {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $vacancy = CustomJobVacancy::factory()->for($otherUser)->create();
        $application = CustomJobApplication::factory()->for($otherUser)->for($vacancy)->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(route('custom-vacancies.show', $application));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    });

    it('requires authentication to list applications', function (): void {
        $response = $this->getJson(route('custom-applications.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    });
});
