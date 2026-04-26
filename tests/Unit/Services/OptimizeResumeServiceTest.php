<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\OptimizeResumeService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

test('optimize returns result', function (): void {
    Http::fake([
        '*' => Http::response([
            'choices' => [[
                'message' => [
                    'content' => 'Optimized resume content',
                ],
            ]],
        ], Response::HTTP_OK),
    ]);

    $service = resolve(OptimizeResumeService::class);
    $result = $service->optimize('My resume', 'Job desc');

    expect($result)->toBeString();
});
