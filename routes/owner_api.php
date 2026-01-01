<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Owner\CompanyController;
use App\Http\Controllers\API\V1\Owner\JobVacancyController;
use Illuminate\Support\Facades\Route;

Route::prefix('owner/dashboard')
    ->middleware(['auth:sanctum', 'owner'])
    ->as('owner.')
    ->group(function (): void {
        Route::apiResource('companies', CompanyController::class);
        Route::apiResource('job-vacancies', JobVacancyController::class);
    });
