<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\ParseJobVacancyService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

test('parse returns parsed data', function (): void {
    Http::fake([
        '*' => Http::response([
            'choices' => [[
                'message' => [
                    'content' => json_encode([
                        'title' => 'PHP Developer',
                        'company' => 'Tech Corp',
                        'description' => 'We need a developer',
                        'location' => 'Remote',
                        'employment_type' => 'Full-time',
                        'responsibilities' => 'Code',
                        'requirements' => 'PHP',
                        'skills_required' => 'Laravel',
                        'experience_years_min' => 2,
                        'experience_years_max' => 5,
                        'expected_salary' => '50000',
                        'category' => 'Tech',
                    ]),
                ],
            ]],
        ], Response::HTTP_OK),
    ]);

    $service = resolve(ParseJobVacancyService::class);
    $result = $service->parse('Job description text');

    expect($result)->toHaveKey('title');
});
