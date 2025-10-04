<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Admin\CategoryController;
use App\Http\Controllers\API\V1\Admin\CompanyController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/dashboard')
    ->middleware(['auth:sanctum', 'admin'])
    ->as('admin.')
    ->group(function (): void {
        Route::resource('companies', CompanyController::class)->except(['show', 'edit', 'create']);
        Route::resource('categories', CategoryController::class)->except(['show', 'edit', 'create']);
    });
