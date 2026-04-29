<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\GroqClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Model::shouldBeStrict();

        /** @var string $model */
        $model = config('ai_services.model');
        /** @var float $temperature */
        $temperature = config('ai_services.temperature');
        /** @var string $apiKey */
        $apiKey = config('services.groq.api_key');
        /** @var string $apiChat */
        $apiChat = config('services.groq.api_chat');
        /** @var int $timeout */
        $timeout = config('ai_services.timeout');

        $this->app->singleton(GroqClient::class, fn (): GroqClient => new GroqClient(
            model: $model,
            temperature: $temperature,
            apiKey: $apiKey,
            apiChat: $apiChat,
            timeout: $timeout,
        ));
    }
}
