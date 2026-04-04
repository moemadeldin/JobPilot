<?php

declare(strict_types=1);
use App\Enums\MockInterviewStatus;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\MockInterviewQuestion;
use App\Models\Resume;
use App\Models\User;
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
        ], 200),
    ]);

    $this->user = User::factory()->create();
    $this->company = $this->user->companies()->create([
        'name' => 'Test Company',
        'industry' => 'Technology',
        'address' => 'Test Address',
        'website' => 'https://test.com',
    ]);
    $this->jobVacancy = JobVacancy::factory()->for($this->company)->create([
        'title' => 'Backend Developer',
        'description' => 'We are looking for a skilled Laravel developer',
    ]);
    $this->resume = Resume::factory()->for($this->user)->create([
        'extracted_text' => 'Experienced Laravel developer with 5 years experience',
    ]);
});

describe('MockInterviewController', function (): void {
    it('shows mock interview questions when accepted', function (): void {
        $application = JobApplication::factory()->for($this->user)->for($this->jobVacancy)->for($this->resume)->create([
            'mock_interview_status' => MockInterviewStatus::ACCEPTED,
        ]);

        MockInterviewQuestion::factory()->count(3)->for($application)->create([
            'question' => 'Test Question',
            'answer' => 'Test Answer',
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson(route('mock.show', $application));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => ['order', 'question', 'answer'],
            ],
        ]);
    });

    it('returns empty when mock interview not accepted', function (): void {
        $application = JobApplication::factory()->for($this->user)->for($this->jobVacancy)->for($this->resume)->create([
            'mock_interview_status' => MockInterviewStatus::SUGGESTED,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson(route('mock.show', $application));

        $response->assertOk();
        $response->assertJson([
            'data' => [],
            'message' => 'Mock interview not accepted or not available.',
        ]);
    });

    it('returns empty when no questions available', function (): void {
        $application = JobApplication::factory()->for($this->user)->for($this->jobVacancy)->for($this->resume)->create([
            'mock_interview_status' => MockInterviewStatus::ACCEPTED,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson(route('mock.show', $application));

        $response->assertOk();
        $response->assertJson([
            'data' => [],
            'message' => 'No mock interview questions available.',
        ]);
    });

    it('accepts mock interview and generates questions', function (): void {
        $application = JobApplication::factory()->for($this->user)->for($this->jobVacancy)->for($this->resume)->create([
            'mock_interview_status' => MockInterviewStatus::SUGGESTED,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson(route('mock.store', $application));

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'questions' => [
                    '*' => ['order', 'question', 'answer'],
                ],
            ],
        ]);

        $application->refresh();
        expect($application->mock_interview_status)->toBe(MockInterviewStatus::ACCEPTED);
    });

    it('returns conflict when mock interview already accepted', function (): void {
        $application = JobApplication::factory()->for($this->user)->for($this->jobVacancy)->for($this->resume)->create([
            'mock_interview_status' => MockInterviewStatus::ACCEPTED,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson(route('mock.store', $application));

        $response->assertStatus(409);
        $response->assertJson([
            'message' => 'Mock interview already accepted for this application.',
        ]);
    });

    it('declines mock interview', function (): void {
        $application = JobApplication::factory()->for($this->user)->for($this->jobVacancy)->for($this->resume)->create([
            'mock_interview_status' => MockInterviewStatus::SUGGESTED,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(route('mock.destroy', $application));

        $response->assertNoContent();

        $application->refresh();
        expect($application->mock_interview_status)->toBe(MockInterviewStatus::DECLINED);
    });

    it('returns conflict when trying to decline already accepted interview', function (): void {
        $application = JobApplication::factory()->for($this->user)->for($this->jobVacancy)->for($this->resume)->create([
            'mock_interview_status' => MockInterviewStatus::ACCEPTED,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(route('mock.destroy', $application));

        $response->assertStatus(409);
    });
});
