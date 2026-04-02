<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

final readonly class GroqClient
{
    public function requestJson(string $systemPrompt, string $userPrompt): array
    {
        $content = $this->send($systemPrompt, $userPrompt, jsonMode: true);

        return json_decode($content, true, flags: JSON_THROW_ON_ERROR);
    }

    public function requestText(string $systemPrompt, string $userPrompt): string
    {
        return $this->send($systemPrompt, $userPrompt, jsonMode: false);
    }

    private function send(string $systemPrompt, string $userPrompt, bool $jsonMode): string
    {
        $body = [
            'model' => config('ai_services.model'),
            'temperature' => config('ai_services.temperature'),
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user',   'content' => $userPrompt],
            ],
        ];

        if ($jsonMode) {
            $body['response_format'] = ['type' => 'json_object'];
        }

        $response = Http::withToken(config('services.groq.api_key'))
            ->timeout(config('ai_services.timeout'))
            ->post(config('services.groq.api_chat'), $body)
            ->throw();

        $content = $response->json('choices.0.message.content');

        throw_if($content === null, RuntimeException::class, 'Empty response from AI');

        return (string) $content;
    }
}
