<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Owner\CompanyController;
use Illuminate\Support\Facades\Route;


Route::prefix('owner/dashboard')
    ->middleware(['auth:sanctum', 'owner'])
    ->group(function (): void {
        Route::resource('companies', CompanyController::class)->except(['show', 'edit','create']);
        Route::post('/companies/{company}/restore', [CompanyController::class, 'restore'])->withTrashed();
});