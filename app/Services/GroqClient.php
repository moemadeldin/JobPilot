<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

final readonly class GroqClient
{
    /**
     * @return array<mixed, mixed>
     */
    public function requestJson(string $systemPrompt, string $userPrompt): array
    {
        $content = $this->send($systemPrompt, $userPrompt, jsonMode: true);

        /** @var array<mixed, mixed> */
        return json_decode($content, true, flags: JSON_THROW_ON_ERROR);
    }

    public function requestText(string $systemPrompt, string $userPrompt): string
    {
        return $this->send($systemPrompt, $userPrompt, jsonMode: false);
    }

    private function send(string $systemPrompt, string $userPrompt, bool $jsonMode): string
    {
        /** @var string $model */
        $model = config('ai_services.model');
        /** @var float $temperature */
        $temperature = config('ai_services.temperature');

        $body = [
            'model' => $model,
            'temperature' => $temperature,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user',   'content' => $userPrompt],
            ],
        ];

        if ($jsonMode) {
            $body['response_format'] = ['type' => 'json_object'];
        }

        /** @var string $apiKey */
        $apiKey = config('services.groq.api_key');
        /** @var float|int $timeout */
        $timeout = config('ai_services.timeout');
        /** @var string $apiChat */
        $apiChat = config('services.groq.api_chat');

        $response = Http::withToken($apiKey)
            ->timeout($timeout)
            ->post($apiChat, $body)
            ->throw();

        /** @var string|null $content */
        $content = $response->json('choices.0.message.content');

        throw_if($content === null, RuntimeException::class, 'Empty response from AI');

        return $content;
    }
}
