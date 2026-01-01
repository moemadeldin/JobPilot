<?php

declare(strict_types=1);

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use RuntimeException;

final readonly class MockInterviewService
{
    public function generate(string $resumeText, string $jobDescription): array
    {
        $prompt = $this->getPrompt($resumeText, $jobDescription);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a structured evaluator. Always return only JSON — no text outside of JSON.',
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => 0.3,
        ]);

        $content = $response->choices[0]->message->content ?? null;

        throw_unless($content, RuntimeException::class, 'Empty response from AI');

        $data = json_decode((string) $content, true, 512, JSON_THROW_ON_ERROR);

        return [
            'question' => $data['question'],
            'answer' => $data['answer'],
        ];
    }

    private function getPrompt(string $resumeText, string $jobDescription): string
    {
        return <<<PROMPT
You are an AI HR assistant generating mock interview Q&A for a candidate based on their resume and the job description.

Generate 10-15 highly relevant question-answer pairs commonly asked in interviews for this position. Keep answers concise yet informative.

Respond strictly in valid JSON:
{
  "qa": [
    {"question": "Question 1?", "answer": "Answer 1."},
    {"question": "Question 2?", "answer": "Answer 2."},
    ...
  ]
}

RESUME:
{$resumeText}

JOB DESCRIPTION:
{$jobDescription}
PROMPT;
    }
}
