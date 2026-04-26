<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\EvaluateResumeWithAIService;
use App\Services\GenerateCoverLetterService;
use App\Services\GenerateMockInterviewQAService;
use App\Services\GroqClient;
use App\Services\OptimizeResumeService;
use App\Services\ParseJobVacancyService;
use App\Services\PasswordResetService;
use App\Services\ResumeTextExtractor;
use App\Services\TokenManager;
use App\Services\UserValidator;

test('services instantiate', function (): void {
    expect(resolve(EvaluateResumeWithAIService::class))->not->toBeNull();
    expect(resolve(GenerateCoverLetterService::class))->not->toBeNull();
    expect(resolve(GenerateMockInterviewQAService::class))->not->toBeNull();
    expect(resolve(GroqClient::class))->not->toBeNull();
    expect(resolve(OptimizeResumeService::class))->not->toBeNull();
    expect(resolve(ParseJobVacancyService::class))->not->toBeNull();
    expect(resolve(PasswordResetService::class))->not->toBeNull();
    expect(resolve(ResumeTextExtractor::class))->not->toBeNull();
    expect(resolve(TokenManager::class))->not->toBeNull();
    expect(resolve(UserValidator::class))->not->toBeNull();
});
