<?php

declare(strict_types=1);

namespace App\Services;

use App\Traits\HasAiPrompt;

final readonly class EvaluateResumeWithAIService
{
    use HasAiPrompt;

    private const string SYSTEM_PROMPT = 'You are a structured evaluator. Always return only valid JSON.';

    public function __construct(private GroqClient $client) {}

    public function evaluate(string $resumeText, string $jobDescription): array
    {
        $prompt = $this->getPrompt($resumeText, $jobDescription, 'prompts.evaluation');

        $data = $this->client->requestJson(self::SYSTEM_PROMPT, $prompt);

        return [
            'score' => $data['score'] ?? 0,
            'feedback' => [
                'strengths' => $data['feedback']['strengths'] ?? [],
                'weaknesses' => $data['feedback']['weaknesses'] ?? [],
            ],
            'suggestions' => $data['suggestions'] ?? 'No suggestions available.',
        ];
    }
}
