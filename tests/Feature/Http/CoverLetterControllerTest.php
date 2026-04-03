<?php

declare(strict_types=1);

use App\Enums\EmploymentType;
use App\Enums\Status;
use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use App\Services\GenerateCoverLetterService;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    Http::fake([
        '*' => Http::response([
            'choices' => [[
                'message' => [
                    'content' => 'Generated cover letter content',
                ],
            ]],
        ], 200),
    ]);

    $this->user = User::factory()->create();
    $this->company = Company::factory()->create(['user_id' => $this->user->id]);
    $this->jobVacancy = JobVacancy::factory()->for($this->company)->create([
        'description' => 'We are looking for a Laravel developer with experience in API development.',
    ]);
    $this->resume = Resume::factory()->for($this->user)->create([
        'extracted_text' => 'Experienced Laravel developer with 5 years of API development.',
    ]);
});

describe('CoverLetterController', function (): void {
    it('generates cover letter successfully', function (): void {
        Sanctum::actingAs($this->user);

        $response = $this->postJson(route('jobs.cover-letter', [
            'job' => $this->jobVacancy->id,
            'resume' => $this->resume->id,
        ]));

        $response->assertCreated();
        $response->assertJsonStructure([
            'data',
            'message',
        ]);
    });

    it('fails when resume has no extracted text', function (): void {
        $otherUser = User::factory()->create();
        $resume = Resume::factory()->for($otherUser)->create([
            'extracted_text' => null,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->postJson(route('jobs.cover-letter', [
            'job' => $this->jobVacancy->id,
            'resume' => $resume->id,
        ]));

        $response->assertStatus(404);
    });

    it('fails when resume does not belong to user', function (): void {
        $otherUser = User::factory()->create();
        $otherResume = Resume::factory()->for($otherUser)->create();

        Sanctum::actingAs($this->user);

        $response = $this->postJson(route('jobs.cover-letter', [
            'job' => $this->jobVacancy->id,
            'resume' => $otherResume->id,
        ]));

        $response->assertStatus(404);
    });

    it('requires authentication', function (): void {
        $response = $this->postJson(route('jobs.cover-letter', [
            'job' => $this->jobVacancy->id,
            'resume' => $this->resume->id,
        ]));

        $response->assertStatus(401);
    });

    it('fails when job does not exist', function (): void {
        Sanctum::actingAs($this->user);

        $response = $this->postJson(route('jobs.cover-letter', [
            'job' => 'nonexistent-job-id',
            'resume' => $this->resume->id,
        ]));

        $response->assertStatus(404);
    });
});

describe('GenerateCoverLetterService', function (): void {
    it('generates cover letter from resume and job description', function (): void {
        $service = app(GenerateCoverLetterService::class);

        $result = $service->generate(
            'Experienced Laravel developer',
            'Looking for Laravel developer for API work'
        );

        expect($result)->toBeString();
        expect($result)->not->toBeEmpty();
    });

    it('sanitizes text by removing newlines', function (): void {
        $service = app(GenerateCoverLetterService::class);

        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('sanitizeText');
        $method->setAccessible(true);

        $result = $method->invoke($service, "Line 1\nLine 2\r\nLine 3");

        expect($result)->not->toContain("\n");
        expect($result)->not->toContain("\r");
    });

    it('returns empty string for null input', function (): void {
        $service = app(GenerateCoverLetterService::class);

        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('sanitizeText');
        $method->setAccessible(true);

        $result = $method->invoke($service, null);

        expect($result)->toBe('');
    });

    it('trims whitespace from result', function (): void {
        $service = app(GenerateCoverLetterService::class);

        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('sanitizeText');
        $method->setAccessible(true);

        $result = $method->invoke($service, "  Hello World  ");

        expect($result)->toBe('Hello World');
    });
});