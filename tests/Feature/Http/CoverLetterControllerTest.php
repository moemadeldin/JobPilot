<?php

declare(strict_types=1);

use App\Services\GenerateCoverLetterService;
use Illuminate\Support\Facades\Http;

describe('GenerateCoverLetterService', function (): void {
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
    });

    it('generates cover letter from resume and job description', function (): void {
        $service = resolve(GenerateCoverLetterService::class);

        $result = $service->generate(
            'Experienced Laravel developer',
            'Looking for Laravel developer for API work'
        );

        expect($result)->toBeString();
        expect($result)->not->toBeEmpty();
    });

    it('sanitizes text by removing newlines', function (): void {
        $service = resolve(GenerateCoverLetterService::class);

        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('sanitizeText');

        $result = $method->invoke($service, "Line 1\nLine 2\r\nLine 3");

        expect($result)->not->toContain("\n");
        expect($result)->not->toContain("\r");
    });

    it('returns empty string for null input', function (): void {
        $service = resolve(GenerateCoverLetterService::class);

        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('sanitizeText');

        $result = $method->invoke($service, null);

        expect($result)->toBe('');
    });

    it('trims whitespace from result', function (): void {
        $service = resolve(GenerateCoverLetterService::class);

        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('sanitizeText');

        $result = $method->invoke($service, '  Hello World  ');

        expect($result)->toBe('Hello World');
    });
});
