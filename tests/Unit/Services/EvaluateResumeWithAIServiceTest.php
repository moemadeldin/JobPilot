<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\EvaluateResumeWithAIService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

test('evaluate returns result', function (): void {
    Http::fake([
        '*' => Http::response([
            'choices' => [[
                'message' => [
                    'content' => json_encode([
                        'score' => 85,
                        'feedback' => ['strengths' => ['PHP'], 'weaknesses' => []],
                        'suggestions' => 'Good',
                    ]),
                ],
            ]],
        ], Response::HTTP_OK),
    ]);

    $service = resolve(EvaluateResumeWithAIService::class);
    $result = $service->evaluate('My resume', 'Job desc');

    expect($result)->toHaveKey('score');
});
