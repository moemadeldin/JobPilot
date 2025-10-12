<?php

declare(strict_types=1);

namespace App\Providers;

use App\Interfaces\Auth\PasswordResetInterface;
use App\Interfaces\Auth\TokenManagerInterface;
use App\Interfaces\Auth\UserValidatorInterface;
use App\Interfaces\ResumeTextExtractorInterface;
use App\Services\PasswordResetService;
use App\Services\ResumeTextExtractor;
use App\Services\TokenManager;
use App\Services\UserValidator;
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
        $this->app->bind(
            TokenManagerInterface::class,
            TokenManager::class
        );
        $this->app->bind(
            UserValidatorInterface::class,
            UserValidator::class
        );
        $this->app->bind(
            ResumeTextExtractorInterface::class,
            ResumeTextExtractor::class
        );
        $this->app->bind(
            PasswordResetInterface::class,
            PasswordResetService::class
        );
    }
}
