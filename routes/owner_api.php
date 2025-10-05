<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Owner\CompanyController;
use App\Http\Controllers\API\V1\Owner\JobVacancyController;
use Illuminate\Support\Facades\Route;

Route::prefix('owner/dashboard')
    ->middleware(['auth:sanctum', 'owner'])
    ->as('owner.')
    ->group(function (): void {
        Route::resource('companies', CompanyController::class)->except(['show', 'edit', 'create']);
        Route::resource('job-vacancies', JobVacancyController::class)->except(['edit', 'create']);
    });
