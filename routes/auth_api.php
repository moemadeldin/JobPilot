<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Auth\SessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->post('/logout', [SessionController::class, 'destroy'])->name('logout.post');

Route::middleware('auth:sanctum')
    ->controller(SessionController::class)
    ->group(function (): void {
        Route::delete('/logout', 'destroy')->name('logout.delete');
        Route::get('/me', 'show')->name('me.show');
    });
