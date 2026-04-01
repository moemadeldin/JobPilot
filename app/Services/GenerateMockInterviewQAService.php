<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Traits\HasAiPrompt;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final readonly class GenerateMockInterviewQAService
{
    use HasAiPrompt;

    private const MODEL = 'llama-3.3-70b-versatile';

    public function generate(string $resumeText, string $jobDescription): array
    {
        $prompt = $this->getPrompt($resumeText, $jobDescription, 'prompts.mock_interview');

        $response = Http::withToken(config('services.groq.api_key'))
            ->timeout(60)
            ->post(config('services.groq.api_chat'), [
                'model' => self::MODEL,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a structured interviewer. Always return only valid JSON.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.3,
                'response_format' => ['type' => 'json_object'],
            ]);

        $response->throw();

        $content = $response->json('choices.0.message.content');

        throw_unless($content, RuntimeException::class, 'Empty response from AI');

        $data = json_decode((string) $content, true, 512, JSON_THROW_ON_ERROR);

        return $data['qa'] ?? [];
    }
}
