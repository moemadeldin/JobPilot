<?php

declare(strict_types=1);

use App\Models\CustomJobApplication;
use App\Models\CustomJobVacancy;
use App\Models\MockInterview;
use App\Models\MockInterviewQuestion;
use App\Models\Resume;
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
                        ['question' => 'Q1', 'answer' => 'A1'],
                        ['question' => 'Q2', 'answer' => 'A2'],
                    ]),
                ],
            ]],
        ], Response::HTTP_OK),
    ]);
});

describe('CustomMockInterviewController', function (): void {
    it('returns mock interview questions when available', function (): void {
        $user = User::factory()->create();
        $resume = Resume::factory()->for($user)->create([
            'extracted_text' => 'Experienced Laravel developer with 5 years experience',
        ]);
        $customJobVacancy = CustomJobVacancy::factory()->for($user)->create([
            'title' => 'Backend Developer',
            'job_text' => 'We are looking for a skilled Laravel developer',
        ]);
        $application = CustomJobApplication::factory()
            ->for($user)
            ->for($customJobVacancy)
            ->create();

        $mockInterview = MockInterview::factory()->for($application, 'application')->create();

        MockInterviewQuestion::factory()->count(3)->for($mockInterview, 'mockInterview')->create([
            'question' => 'Test Question',
            'answer' => 'Test Answer',
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(route('mock.interview', $application));

        $response->assertOk();
    });

    it('returns empty when no mock interview questions available', function (): void {
        $user = User::factory()->create();
        $resume = Resume::factory()->for($user)->create([
            'extracted_text' => 'Experienced Laravel developer with 5 years experience',
        ]);
        $customJobVacancy = CustomJobVacancy::factory()->for($user)->create([
            'title' => 'Backend Developer',
            'job_text' => 'We are looking for a skilled Laravel developer',
        ]);
        $application = CustomJobApplication::factory()
            ->for($user)
            ->for($customJobVacancy)
            ->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(route('mock.interview', $application));

        $response->assertOk();
        $response->assertJson([
            'data' => [],
        ]);
    });

    it('requires authentication', function (): void {
        $user = User::factory()->create();
        Resume::factory()->for($user)->create([
            'extracted_text' => 'Experienced Laravel developer with 5 years experience',
        ]);
        $customJobVacancy = CustomJobVacancy::factory()->for($user)->create([
            'title' => 'Backend Developer',
            'job_text' => 'We are looking for a skilled Laravel developer',
        ]);
        $application = CustomJobApplication::factory()
            ->for($user)
            ->for($customJobVacancy)
            ->create();

        $response = $this->getJson(route('mock.interview', $application));

        $response->assertStatus(401);
    });
});
