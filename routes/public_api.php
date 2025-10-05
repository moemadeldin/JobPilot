<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\Auth\SessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::controller(SessionController::class)->group(function (): void {
        Route::post('/login', 'store')->name('login.store');
    });
    Route::post('/register', RegisterController::class)->name('register.store');
});
