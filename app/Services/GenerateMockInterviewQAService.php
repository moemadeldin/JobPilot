<?php

declare(strict_types=1);

namespace App\Services;

use App\Traits\HasAiPrompt;

final readonly class GenerateMockInterviewQAService
{
    use HasAiPrompt;

    private const string SYSTEM_PROMPT =
        'You are a structured interviewer. Always return only valid JSON.';

    public function __construct(private GroqClient $client) {}

    /**
     * @return array<int, array{question: string, answer: string}>
     */
    public function generate(string $resumeText, string $jobDescription): array
    {
        $prompt = $this->getPrompt(
            $resumeText,
            $jobDescription,
            'prompts.mock_interview'
        );

        $response = $this->client->requestJson(self::SYSTEM_PROMPT, $prompt);

        $qaList = $response['qa'] ?? [];

        return array_values(array_filter($qaList, fn ($item): bool => is_array($item)
            && isset($item['question'], $item['answer'])
            && is_string($item['question'])
            && is_string($item['answer'])));
    }
}
