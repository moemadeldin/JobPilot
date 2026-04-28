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

        $this->app->singleton(GroqClient::class, fn (): GroqClient => new GroqClient(
            model: config('ai_services.model'),
            temperature: config('ai_services.temperature'),
            apiKey: config('services.groq.api_key'),
            apiChat: config('services.groq.api_chat'),
            timeout: config('ai_services.timeout'),
        ));
    }
}
