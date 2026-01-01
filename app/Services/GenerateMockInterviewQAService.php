<?php

declare(strict_types=1);

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use RuntimeException;

final readonly class GenerateMockInterviewQAService
{
    public function generate(string $resumeText, string $jobDescription): array
    {
        $prompt = $this->getPrompt($resumeText, $jobDescription);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a structured interviewer. Always return only JSON — no text outside of JSON.',
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

        return $data['qa'] ?? [];
    }

    private function getPrompt(string $resumeText, string $jobDescription): string
    {
        $template = config('openai.prompts.mock_interview');

        return str_replace(
            ['{resume}', '{job_description}'],
            [$resumeText, $jobDescription],
            $template
        );
    }
}
