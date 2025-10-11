<?php

declare(strict_types=1);

namespace App\Providers;

use App\Interfaces\Auth\TokenManagerInterface;
use App\Interfaces\Auth\UserValidatorInterface;
use App\Interfaces\CompanyServiceInterface;
use App\Interfaces\JobCategoryInterface;
use App\Interfaces\ResumeTextExtractorInterface;
use App\Services\CompanyService;
use App\Services\JobCategoryService;
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
            JobCategoryInterface::class,
            JobCategoryService::class
        );
        $this->app->bind(
            CompanyServiceInterface::class,
            CompanyService::class
        );
        $this->app->bind(
            ResumeTextExtractorInterface::class,
            ResumeTextExtractor::class
        );
    }
}
