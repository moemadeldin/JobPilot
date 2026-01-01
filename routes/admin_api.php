<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Admin\CategoryController;
use App\Http\Controllers\API\V1\Admin\CompanyController;
use App\Http\Controllers\API\V1\Admin\JobVacancyController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/dashboard')
    ->middleware(['auth:sanctum', 'admin'])
    ->as('admin.')
    ->group(function (): void {
        Route::apiResource('companies', CompanyController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('job-vacancies', JobVacancyController::class);
    });
