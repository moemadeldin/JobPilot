<?php

declare(strict_types=1);

use App\Models\CustomJobVacancy;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    Http::fake([
        '*' => Http::response([
            'choices' => [[
                'message' => [
                    'content' => json_encode([
                        'title' => 'Laravel Developer',
                        'company' => 'Tech Corp',
                        'skills_required' => 'Laravel, PHP',
                        'responsibilities' => 'Build APIs',
                        'requirements' => '3+ years experience',
                        'experience_years_min' => 3,
                        'experience_years_max' => 5,
                        'nice_to_have' => 'React knowledge',
                        'location' => 'Remote',
                    ]),
                ],
            ]],
        ], Response::HTTP_OK),
    ]);
});

describe('CustomJobVacancyController', function (): void {
    it('can list custom job vacancies', function (): void {
        $user = User::factory()->create();
        CustomJobVacancy::factory()->count(3)->for($user)->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(route('custom-vacancies.index'));

        $response->assertOk();
    });

    it('can store a custom job vacancy and application', function (): void {
        $user = User::factory()->create();
        $user->resume()->create([
            'name' => 'resume.pdf',
            'path' => 'resumes/test.pdf',
            'extracted_text' => '5 years Laravel experience',
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson(route('custom-vacancies.store'), [
            'job_text' => 'Looking for a Laravel developer with 5 years experience.',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'vacancy',
                'application',
                'mock_interview',
            ],
        ]);
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
