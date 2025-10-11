<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use RuntimeException;
use Throwable;

final readonly class EvaluateResumeWithAIService
{
    public function evaluate(string $resumeText, string $jobDescription): array
    {
        $prompt = <<<PROMPT
You are an AI HR assistant evaluating how well a candidate's resume fits a job description.

Compare the following RESUME and JOB DESCRIPTION, then respond **strictly in valid JSON**:
{
  "score": number (integer 0–100),
  "feedback": {
    "strengths": string[],
    "weaknesses": string[]
  },
  "suggestions": string
}

RESUME:
{$resumeText}

JOB DESCRIPTION:
{$jobDescription}
PROMPT;

        try {
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
                'temperature' => 0.3, //
            ]);

            $content = $response->choices[0]->message->content ?? null;

            if (! $content) {
                throw new RuntimeException('Empty response from AI');
            }

            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            return [
                'score' => $data['score'] ?? 0,
                'feedback' => [
                    'strengths' => $data['feedback']['strengths'] ?? [],
                    'weaknesses' => $data['feedback']['weaknesses'] ?? [],
                ],
                'suggestions' => $data['suggestions'] ?? 'No suggestions available.',
            ];
        } catch (Throwable $e) {
            Log::error('AI evaluation failed: '.$e->getMessage(), [
                'resume_snippet' => mb_substr($resumeText, 0, 100),
            ]);

            return [
                'score' => 0,
                'feedback' => [
                    'strengths' => [],
                    'weaknesses' => [],
                ],
                'suggestions' => 'AI evaluation failed. Please try again later.',
            ];
        }
    }
}
